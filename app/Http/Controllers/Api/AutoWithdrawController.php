<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\CryptoPayout;
use App\Models\RequestAmount;
use Validator;
use Web3p\EthereumTx\Transaction; // from web3p/ethereum-tx
use kornrunner\Keccak;

class AutoWithdrawController extends Controller
{
    protected function rpcForChain(string $chain = 'bsc'): string
    {
        return match (strtolower($chain)) {
            'polygon' => env('RPC_POLYGON', 'https://polygon-rpc.com'),
            'eth', 'ethereum' => env('RPC_ETH', 'https://mainnet.infura.io/v3/YOUR_KEY'),
            default => env('RPC_BSC', 'https://bsc-dataseed.binance.org/'),
        };
    }

    // helper: convert decimal string to wei-string using bc math
    protected function decimalToWeiString(string $decimalStr, int $decimals): string
    {
        if (strpos($decimalStr, '.') !== false) {
            [$intPart, $fracPart] = explode('.', $decimalStr);
        } else {
            $intPart = $decimalStr;
            $fracPart = '';
        }
        $fracPart = str_pad(substr($fracPart, 0, $decimals), $decimals, '0');
        $combined = ltrim($intPart . $fracPart, '0');
        return $combined === '' ? '0' : $combined;
    }

    protected function hexToDecString(string $hex): string
    {
        if (!$hex) return '0';
        if (str_starts_with($hex, '0x')) $hex = substr($hex, 2);
        $dec = '0';
        for ($i = 0; $i < strlen($hex); $i++) {
            $dec = bcmul($dec, '16', 0);
            $dec = bcadd($dec, hexdec($hex[$i]), 0);
        }
        return $dec;
    }

    // get token decimals (call decimals() on contract)
    protected function getTokenDecimals(string $token, string $rpc): int
    {
        $data = '0x313ce567';
        $resp = Http::post($rpc, [
            'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_call',
            'params'=>[[ 'to'=>$token, 'data'=>$data ], 'latest']
        ]);
        $res = $resp->json('result');
        return $res ? hexdec($res) : 18;
    }

    /**
     * Process automatic on-chain payout: signs and broadcasts transaction using ADMIN_PRIVATE_KEY
     */
    public function processAuto(Request $r, $id)
    {
        $validator = Validator::make($r->all(), [
            'token_contract' => 'required|string',
            'amount' => 'required',
            'chain' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['ok'=>false,'error'=>'validation','errors'=>$validator->errors()], 422);
        }

        $chain = $r->query('chain', $r->input('chain', 'bsc'));
        $rpc = $this->rpcForChain($chain);
        $token = strtolower($r->token_contract);
        $amountDecimal = (string) $r->amount;

        // read decimals
        $decimals = $this->getTokenDecimals($token, $rpc);
        $amountWeiStr = $this->decimalToWeiString($amountDecimal, $decimals);

        // admin keys
        $adminAddress = strtolower(env('ADMIN_ADDRESS', ''));
        $adminPriv = env('ADMIN_PRIVATE_KEY', ''); // WITHOUT 0x

        if (empty($adminAddress) || empty($adminPriv)) {
            return response()->json(['ok'=>false,'error'=>'server_misconfigured','message'=>'Admin address or private key missing'], 500);
        }

        DB::beginTransaction();
        try {
            // create payout DB record (pending)
            $payout = CryptoPayout::create([
                'user_id' => $r->user_id ?? 0,
                'token' => $token,
                'chain' => strtolower($chain),
                'from' => $adminAddress,
                'to' => strtolower($r->receiver ?? $r->to ?? ''),
                'amount' => $amountDecimal,
                'amount_wei' => $amountWeiStr,
                'status' => 'pending',
            ]);

            // build ERC20 transfer calldata:
            // methodId for transfer(address,uint256) is a9059cbb
            $method = 'a9059cbb';
            $toAddress = strtolower($r->receiver ?? $r->to ?? '');
            $toClean = str_pad(ltrim($toAddress, '0x'), 64, '0', STR_PAD_LEFT);
            $valueHex = gmp_strval(gmp_init($amountWeiStr, 10), 16);
            if (strlen($valueHex) % 2 === 1) $valueHex = '0' . $valueHex;
            $valueClean = str_pad($valueHex, 64, '0', STR_PAD_LEFT);
            $data = '0x' . $method . $toClean . $valueClean;

            // get nonce
            $nonceResp = Http::post($rpc, [
                'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_getTransactionCount',
                'params'=>[ $adminAddress, 'pending' ]
            ]);
            $nonceHex = $nonceResp->json('result') ?? '0x0';
            $nonce = hexdec($nonceHex);

            // get gas price (simple) and estimate gas
            $gasPriceResp = Http::post($rpc, [
                'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_gasPrice','params'=>[]
            ]);
            $gasPriceHex = $gasPriceResp->json('result') ?? '0x59682f00'; // fallback
            $gasPrice = hexdec($gasPriceHex);

            // estimate gas (call eth_estimateGas)
            $estResp = Http::post($rpc, [
                'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_estimateGas',
                'params'=>[
                    [
                        'from' => $adminAddress,
                        'to' => $token, // token contract
                        'value' => '0x0',
                        'data' => $data
                    ]
                ]
            ]);
            $gasLimitHex = $estResp->json('result') ?? null;
            $gasLimit = $gasLimitHex ? hexdec($gasLimitHex) : 150000; // fallback

            // chain id number
            $chainId = match (strtolower($chain)) {
                'polygon' => 137,
                'eth', 'ethereum' => 1,
                default => 56,
            };

            // Build transaction object compatible with web3p/ethereum-tx
            // nonce, gasPrice, gasLimit, to (contract), value = 0, data
            $txParams = [
                'nonce' => '0x' . dechex($nonce),
                'gasPrice' => '0x' . dechex($gasPrice),
                'gasLimit' => '0x' . dechex($gasLimit),
                'to' => $token,
                'value' => '0x0',
                'data' => $data,
                'chainId' => $chainId
            ];

            // Create and sign transaction
            // NOTE: web3p Transaction expects hex strings (without 0x?) check version docs
            $transaction = new Transaction($txParams);
            $signedTx = '0x' . $transaction->sign($adminPriv);

            // send raw tx
            $sendResp = Http::post($rpc, [
                'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_sendRawTransaction',
                'params' => [ $signedTx ]
            ]);
            $sendBody = $sendResp->json();
            if (isset($sendBody['error'])) {
                // update payout status to failed
                $payout->status = 'failed';
                $payout->save();
                DB::commit();
                return response()->json(['ok'=>false,'error'=>'rpc_error','message'=>$sendBody['error']], 500);
            }

            $txHash = $sendBody['result'] ?? null;
            if (!$txHash) {
                $payout->status = 'failed';
                $payout->save();
                DB::commit();
                return response()->json(['ok'=>false,'error'=>'no_tx_hash','message'=>'No tx hash from RPC'], 500);
            }

            // update DB with tx hash and set as pending -> we'll poll later to confirm
            $payout->tx_hash = $txHash;
            $payout->status = 'pending';
            $payout->save();

            DB::commit();

            // return success and tx hash
            return response()->json(['ok'=>true,'tx_hash'=>$txHash,'payout_id'=>$payout->id]);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('auto withdraw failed: '.$e->getMessage(), ['exc'=>$e]);
            return response()->json(['ok'=>false,'error'=>'server_error','message'=>$e->getMessage()], 500);
        }
    }
}
