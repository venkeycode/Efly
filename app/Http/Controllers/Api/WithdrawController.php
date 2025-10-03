<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\CryptoPayout; // make sure this model exists
use Web3p\EthereumTx\Transaction;
use kornrunner\Keccak;

class WithdrawController extends Controller
{
    // Config (BSC mainnet + USDT)
    private const USDT_DECIMALS = 18; // USDT on BSC uses 18
    private const USDT_TRANSFER_SIG = 'a9059cbb'; // transfer(address,uint256)

    public function sendUsdt(Request $r)
    {
        // dd($r);
        $r->validate([
            'user_id'    => 'required|integer',
            'to'         => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
            'amount'     => 'required|numeric|min:0.000000000000000001', // decimal USDT
            'reference'  => 'nullable|string|max:100',
        ]);


        // env & sanity checks
        $adminAddr = strtolower(env('ADMIN_ADDRESS'));
        $pk        = env('ADMIN_PRIVATE_KEY');              // without 0x
        $rpc       = env('BSC_RPC_URL', 'https://bsc-dataseed.binance.org/');
        $usdt      = strtolower(env('USDT_BSC', '0x55d398326f99059fF775485246999027B3197955'));

        if (!$adminAddr || !$pk) {
            return response()->json(['ok'=>false,'error'=>'Admin wallet not configured'], 500);
        }

        $to = strtolower($r->to);
        $amount = $r->amount;
        $amount = $amount - ($amount * 0.10);
        $amount = (string)$amount;

        // try {
            // 1) Ensure admin has native gas
            $adminNativeHex = $this->rpc($rpc, 'eth_getBalance', [$adminAddr, 'latest']);
            $adminNativeWei = $this->hexToDec($adminNativeHex ?? '0x0');
            if (bccomp($adminNativeWei, $this->parseUnits('0.0002', 18)) < 0) {
                return response()->json(['ok'=>false,'error'=>'Admin wallet has insufficient BNB for gas'], 400);
            }

            // 2) Build ERC20 transfer data
            $amountWei = $this->parseUnits($amount, self::USDT_DECIMALS); // decimal -> wei (string)
            $data = '0x' . self::USDT_TRANSFER_SIG
                  . $this->pad32(substr($to, 2))
                  . $this->pad32($this->decToHex($amountWei));

            // 3) Nonce, gas price, estimate gas
            $nonceHex = $this->rpc($rpc, 'eth_getTransactionCount', [$adminAddr, 'pending']);
            $gasPriceHex = $this->rpc($rpc, 'eth_gasPrice', []);
            $estimate = $this->rpc($rpc, 'eth_estimateGas', [[
                'from' => $adminAddr,
                'to'   => $usdt,
                'data' => $data,
            ]]);

            // Fallbacks
            $gasLimitHex = $estimate ?: '0x249f0'; // ~150000
            if (!$gasPriceHex) { $gasPriceHex = '0x' . dechex(5 * 10**9); } // 5 gwei

            // 4) Chain id
            $chainIdHex = $this->rpc($rpc, 'eth_chainId', []);
            $chainId = $chainIdHex ? hexdec($chainIdHex) : 56; // BSC

            // 5) Build & sign tx
            $tx = new Transaction([
                'nonce'    => $nonceHex,
                'gasPrice' => $gasPriceHex,
                'gas'      => $gasLimitHex,
                'to'       => $usdt,
                'value'    => '0x0',
                'data'     => $data,
                'chainId'  => $chainId,
            ]);

            $signed = '0x' . $tx->sign($pk);

            // 6) Broadcast
            $txHash = $this->rpc($rpc, 'eth_sendRawTransaction', [$signed]);
            if (!$txHash) {
                return response()->json(['ok'=>false,'error'=>'Broadcast failed'], 500);
            }

            // 7) Persist as pending
            $payout = CryptoPayout::create([
                'user_id'     => $r->user_id,
                'token'       => $usdt,
                'chain'       => 'bsc',
                'from'        => $adminAddr,
                'to'          => $to,
                'amount'      => $amount,   // human-readable
                'amount_wei'  => $amountWei,
                'tx_hash'     => $txHash,
                'status'      => 'pending',
                'reference'   => $r->reference,
            ]);

            // 8) Wait for 1 confirmation (optional but helpful)
            $receipt = $this->waitForReceipt($rpc, $txHash, 40, 3.0);
            if ($receipt && isset($receipt['status']) && hexdec($receipt['status']) === 1) {
                $payout->status = 'confirmed';
                $payout->save();

                return response()->json([
                    'ok'      => true,
                    'tx_hash' => $txHash,
                    'status'  => 'confirmed'
                ]);
            } else {
                // still pending
                return response()->json([
                    'ok'      => true,
                    'tx_hash' => $txHash,
                    'status'  => 'pending'
                ]);
            }

        // } catch (\Throwable $e) {
        //     return response()->json([
        //         'ok' => false,
        //         'error' => 'Withdraw failed',
        //         'details' => $e->getMessage()
        //     ], 500);
        // }
    }

    /* ---------------- Helpers ---------------- */

    private function rpc(string $rpc, string $method, array $params)
    {
        $res = Http::withHeaders(['Content-Type'=>'application/json'])
            ->post($rpc, [
                'jsonrpc' => '2.0',
                'id'      => (string) (microtime(true)*1000),
                'method'  => $method,
                'params'  => $params
            ]);

        if (!$res->ok()) throw new \RuntimeException("RPC $method HTTP ".$res->status().": ".$res->body());

        $json = $res->json();
        if (isset($json['error'])) {
            throw new \RuntimeException("RPC $method error: ".json_encode($json['error']));
        }
        return $json['result'] ?? null;
    }

    // decimal string -> wei string (no hex)
    private function parseUnits(string $decimal, int $decimals): string
    {
        if (strpos($decimal, '.') === false) {
            return bcmul($decimal, bcpow('10', (string)$decimals, 0), 0);
        }
        [$int, $frac] = explode('.', $decimal, 2);
        $frac = substr($frac, 0, $decimals);
        $frac = str_pad($frac, $decimals, '0');
        $combined = ltrim($int.$frac, '0');
        return $combined === '' ? '0' : $combined;
    }

    private function decToHex(string $dec): string
    {
        // large decimal -> hex (no 0x)
        $hex = '';
        $num = $dec;
        if ($num === '0') return '0';
        while (bccomp($num, '0') > 0) {
            $rem = bcmod($num, '16');
            $hex = dechex((int)$rem) . $hex;
            $num = bcdiv($num, '16', 0);
        }
        return $hex;
    }

    private function hexToDec(string $hex): string
    {
        if (str_starts_with($hex, '0x')) $hex = substr($hex, 2);
        $dec = '0';
        for ($i = 0; $i < strlen($hex); $i++) {
            $dec = bcmul($dec, '16', 0);
            $dec = bcadd($dec, hexdec($hex[$i]), 0);
        }
        return $dec;
    }

    private function pad32(string $hexNoPrefix): string
    {
        return str_pad(strtolower($hexNoPrefix), 64, '0', STR_PAD_LEFT);
    }

    private function waitForReceipt(string $rpc, string $txHash, int $maxTries = 30, float $sleepSec = 2.0): ?array
    {
        for ($i = 0; $i < $maxTries; $i++) {
            $res = $this->rpc($rpc, 'eth_getTransactionReceipt', [$txHash]);
            if (is_array($res) && isset($res['blockNumber'])) {
                return $res;
            }
            usleep((int)($sleepSec * 1_000_000));
        }
        return null;
    }
}
