<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use App\Models\CryptoPayment;
class WalletController extends Controller
{



public function verifyTokenPayment(Request $r)
{
    $r->validate([
        'user_id'       => 'required|integer',
        'token_contract'=> 'required|string',
        'amount'        => 'required|string',
        'tx_hash'       => 'required|string',
        'from_address'  => 'required|string',
        'receiver'      => 'required|string',
    ]);

    $rpc = env('RPC_BSC', 'https://bsc-dataseed.binance.org/');
    $token = strtolower($r->token_contract);
    $receiver = strtolower($r->receiver);
    $from = strtolower($r->from_address);

    // 1. Get transaction details
    $txResp = Http::post($rpc, [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => 'eth_getTransactionByHash',
        'params' => [$r->tx_hash],
    ]);
    $tx = $txResp->json('result');
    if (!$tx) {
        return response()->json(['ok'=>false,'error'=>'Transaction not found'], 200);
    }

    // 2. Verify tx.to == token contract
    if (strtolower($tx['to'] ?? '') !== $token) {
        return response()->json(['ok'=>false,'error'=>'Transaction not sent to token contract'], 400);
    }

    // 3. Decode input data
    $input = $tx['input'] ?? '';
    if (substr($input, 0, 10) !== "0xa9059cbb") {
        return response()->json(['ok'=>false,'error'=>'Not a transfer() call'], 400);
    }

    $toAddr = "0x".substr($input, 34, 40);
    $valueHex = "0x".ltrim(substr($input, 74), '0');
    $valueDec = $this->hexToDecString($valueHex);

    if (strtolower($toAddr) !== $receiver) {
        return response()->json(['ok'=>false,'error'=>'Receiver mismatch'], 400);
    }
    if (strtolower($tx['from']) !== $from) {
        return response()->json(['ok'=>false,'error'=>'Sender mismatch'], 400);
    }

    // 4. Verify amount
    $decimals = 18; // adjust decimals if needed
    $expectedWei = bcmul($r->amount, bcpow('10', (string)$decimals, 0), 0);
    if (bccomp($valueDec, $expectedWei) < 0) {
        return response()->json(['ok'=>false,'error'=>'Insufficient token amount'], 400);
    }

    // 5. Ensure transaction succeeded
    $receiptResp = Http::post($rpc, [
        'jsonrpc'=>'2.0','id'=>2,'method'=>'eth_getTransactionReceipt',
        'params'=>[$r->tx_hash]
    ]);
    $receipt = $receiptResp->json('result');
    if (!$receipt || hexdec($receipt['status'] ?? '0x0') !== 1) {
        return response()->json(['ok'=>false,'error'=>'Tx not successful'], 400);
    }

    // ✅ Store in crypto_payments table
    $payment = CryptoPayment::updateOrCreate(
        ['tx_hash' => $r->tx_hash],
        [
            'user_id'       => $r->user_id,
            'token_contract'=> $token,
            'from_address'  => $from,
            'receiver'      => $receiver,
            'amount'        => $r->amount,
            'status'        => 'success'
        ]
    );

    return response()->json(['ok'=>true,'tx_hash'=>$r->tx_hash, 'payment_id' => $payment->id]);
}

    // public function verifyTokenPayment(Request $r)
    // {
    //     $r->validate([
    //         'user_id' => 'required|integer',
    //         'token_contract' => 'required|string',
    //         'amount' => 'required|string',
    //         'tx_hash' => 'required|string',
    //         'from_address' => 'required|string',
    //         'receiver' => 'required|string'
    //     ]);

    //     $rpc = env('RPC_URL');
    //     $token = strtolower($r->token_contract);
    //     $from = strtolower($r->from_address);
    //     $receiver = strtolower($r->receiver);
    //     $amountDecimal = $r->amount;

    //     // Ensure wallet matches saved one
    //     $savedWallet = DB::table('customdetails')->where('userid', $r->user_id)->value('wallet_address');
    //     if (!$savedWallet || strtolower($savedWallet) !== $from) {
    //         return response()->json(['ok'=>false,'error'=>'Saved wallet mismatch'], 400);
    //     }

    //     // Fetch tx receipt
    //     $receiptResp = Http::post($rpc, [
    //         'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_getTransactionReceipt','params'=>[$r->tx_hash]
    //     ]);
    //     $receipt = $receiptResp->json('result');
    //     if (!$receipt) return response()->json(['ok'=>false,'error'=>'Receipt not found'], 404);
    //     if (hexdec($receipt['status'] ?? '0x0') !== 1) {
    //         return response()->json(['ok'=>false,'error'=>'Transaction failed'], 400);
    //     }

    //     // ERC20 Transfer event topic
    //     $transferTopic = '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef';

    //     $found = false;
    //     foreach ($receipt['logs'] as $log) {
    //         if (strtolower($log['address']) !== $token) continue;
    //         if ($log['topics'][0] !== $transferTopic) continue;

    //         $topicFrom = '0x' . substr($log['topics'][1], 26);
    //         $topicTo   = '0x' . substr($log['topics'][2], 26);

    //         if (strtolower($topicFrom) !== $from) continue;
    //         if (strtolower($topicTo) !== $receiver) continue;

    //         $valueWeiStr = $this->hexToDecString($log['data']);
    //         $decimals = $this->getTokenDecimals($token, $rpc);
    //         $requiredWeiStr = $this->decimalToWeiString($amountDecimal, $decimals);

    //         if (bccomp($valueWeiStr, $requiredWeiStr) >= 0) {
    //             $found = true;
    //             break;
    //         }
    //     }

    //     if (!$found) return response()->json(['ok'=>false,'error'=>'No matching Transfer found'], 400);

    //     return response()->json(['ok'=>true,'tx_hash'=>$r->tx_hash]);
    // }

    // ---- Helpers ----

    private function hexToDecString($hex)
    {
        if (!$hex) return '0';
        if (substr($hex, 0, 2) === '0x') $hex = substr($hex, 2);
        $dec = '0';
        for ($i = 0; $i < strlen($hex); $i++) {
            $dec = bcmul($dec, '16', 0);
            $dec = bcadd($dec, hexdec($hex[$i]), 0);
        }
        return $dec;
    }

    private function formatUnits(string $weiDecString, int $decimals = 18): string
    {
        if ($decimals <= 0) return $weiDecString;
        $len = strlen($weiDecString);
        if ($len <= $decimals) {
            $intPart = '0';
            $fracPart = str_pad($weiDecString, $decimals, '0', STR_PAD_LEFT);
        } else {
            $intPart = substr($weiDecString, 0, $len - $decimals);
            $fracPart = substr($weiDecString, $len - $decimals);
        }
        $fracPart = rtrim($fracPart, '0');
        return $intPart . ($fracPart !== '' ? '.' . $fracPart : '');
    }

    private function decimalToWeiString($decimalStr, $decimals)
    {
        if (strpos($decimalStr, '.') !== false) {
            [$intPart, $fracPart] = explode('.', $decimalStr);
        } else {
            $intPart = $decimalStr;
            $fracPart = '';
        }
        $fracPart = str_pad(substr($fracPart, 0, $decimals), $decimals, '0');
        $combined = $intPart . $fracPart;
        return ltrim($combined, '0') ?: '0';
    }

    private function getTokenDecimals($token, $rpc)
    {
        $resp = Http::post($rpc, [
            'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_call',
            'params'=>[[ 'to'=>$token, 'data'=>'0x313ce567' ], 'latest']
        ]);
        $res = $resp->json('result');
        return $res ? hexdec($res) : 18;
    }
}
