<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->get('user_id');
        return view('website.connect', compact('userId'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        $customer = Customer::find($request->user_id);
        $customer->wallet_address = $request->wallet_address;
        $customer->save();

        return response()->json([
            'ok' => true,
            'wallet_address' => $request->wallet_address,
            'redirect' => 'https://earnnfly.com/New/user/index.php/home'
        ]);
    }
public function getBalance(Request $request, $address)
{
    $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com');
    $chainDecimals = 18;

    $token = $request->query('token');       // optional token contract address
    $decimalsQuery = $request->query('decimals');

    // --- Native balance ---
    $response = Http::post($rpcUrl, [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => 'eth_getBalance',
        'params' => [$address, 'latest'],
    ]);
    $nativeHex = $response->json('result') ?? '0x0';

    $nativeWeiStr = $this->hexToDecString($nativeHex);
    $nativeBalance = $this->formatUnits($nativeWeiStr, $chainDecimals);

    $out = [
        'ok' => true,
        'address' => $address,
        'native' => [
            'balance_wei' => $nativeWeiStr,
            'balance' => $nativeBalance,
            'symbol' => 'MATIC', // since we are on Polygon
        ],
    ];

    // --- ERC20 token balance (e.g., USDT) ---
    if ($token) {
        $token = strtolower($token);

        // 1) decimals
        if ($decimalsQuery !== null && is_numeric($decimalsQuery)) {
            $decimals = intval($decimalsQuery);
        } elseif ($token === strtolower("0xc2132D05D31c914a87C6611C10748AEb04B58e8F")) {
            // hardcode USDT decimals (6) if it's Polygon USDT
            $decimals = 6;
        } else {
            $data = '0x313ce567'; // decimals()
            $decResp = Http::post($rpcUrl, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'eth_call',
                'params' => [[ 'to' => $token, 'data' => $data ], 'latest'],
            ]);
            $decResult = $decResp->json('result');
            $decimals = ($decResult && $decResult !== '0x') ? hexdec($decResult) : 18;
        }

        // 2) balanceOf(address)
        $method = '70a08231';
        $addr = ltrim(strtolower($address), '0x');
        $addr = str_pad($addr, 64, '0', STR_PAD_LEFT);
        $data = '0x' . $method . $addr;

        $balResp = Http::post($rpcUrl, [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => [[ 'to' => $token, 'data' => $data ], 'latest'],
        ]);
        $balResult = $balResp->json('result') ?? '0x0';

        $tokenBalanceWeiStr = $this->hexToDecString($balResult);
        $tokenBalanceFormatted = $this->formatUnits($tokenBalanceWeiStr, $decimals);

        $out['token'] = [
            'contract' => $token,
            'decimals' => $decimals,
            'balance_wei' => $tokenBalanceWeiStr,
            'balance' => $tokenBalanceFormatted,
        ];
    }

    return response()->json($out);
}
    // public function getBalance(Request $request, $address)
    // {
    //     $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com');
    //     $chainDecimals = 18;

    //     $token = $request->query('token');
    //     $decimalsQuery = $request->query('decimals');

    //     // Native balance
    //     $response = Http::post($rpcUrl, [
    //         'jsonrpc' => '2.0',
    //         'id' => 1,
    //         'method' => 'eth_getBalance',
    //         'params' => [$address, 'latest'],
    //     ]);
    //     $nativeHex = $response->json('result') ?? '0x0';

    //     $nativeWeiStr = $this->hexToDecString($nativeHex);
    //     $nativeBalance = $this->formatUnits($nativeWeiStr, $chainDecimals);

    //     $out = [
    //         'ok' => true,
    //         'address' => $address,
    //         'native' => [
    //             'balance_wei' => $nativeWeiStr,
    //             'balance' => $nativeBalance,
    //             'symbol' => 'NATIVE',
    //         ],
    //     ];

    //     // ERC20 token balance
    //     if ($token) {
    //         $token = strtolower($token);
    //         if ($decimalsQuery !== null && is_numeric($decimalsQuery)) {
    //             $decimals = intval($decimalsQuery);
    //         } else {
    //             $data = '0x313ce567'; // decimals()
    //             $decResp = Http::post($rpcUrl, [
    //                 'jsonrpc' => '2.0',
    //                 'id' => 1,
    //                 'method' => 'eth_call',
    //                 'params' => [[ 'to' => $token, 'data' => $data ], 'latest'],
    //             ]);
    //             $decimals = $decResp->json('result') ? hexdec($decResp->json('result')) : 18;
    //         }

    //         $method = '70a08231'; // balanceOf(address)
    //         $addr = ltrim(strtolower($address), '0x');
    //         $addr = str_pad($addr, 64, '0', STR_PAD_LEFT);
    //         $data = '0x' . $method . $addr;

    //         $balResp = Http::post($rpcUrl, [
    //             'jsonrpc' => '2.0',
    //             'id' => 1,
    //             'method' => 'eth_call',
    //             'params' => [[ 'to' => $token, 'data' => $data ], 'latest'],
    //         ]);
    //         $balResult = $balResp->json('result') ?? '0x0';

    //         $tokenBalanceWeiStr = $this->hexToDecString($balResult);
    //         $tokenBalanceFormatted = $this->formatUnits($tokenBalanceWeiStr, $decimals);

    //         $out['token'] = [
    //             'contract' => $token,
    //             'decimals' => $decimals,
    //             'balance_wei' => $tokenBalanceWeiStr,
    //             'balance' => $tokenBalanceFormatted,
    //         ];
    //     }

    //     return response()->json($out);
    // }

    public function showPayPage(Request $r)
    {
        $userId = $r->query('user_id');
        $amount = $r->query('amount');
        $return = $r->query('return');
        $savedWallet = DB::table('customdetails')->where('uid', $userId)->value('wallet_address');
        return view('website.pay', compact('userId','amount','return','savedWallet'));
    }

    /**
     * ✅ Verify ERC20 (USDT) payment
     */
    public function verifyTokenPayment(Request $r)
    {
        $r->validate([
            'user_id' => 'required|integer',
            'token_contract' => 'required|string',
            'amount' => 'required|string',
            'tx_hash' => 'required|string',
            'from_address' => 'required|string',
            'receiver' => 'required|string'
        ]);

        $rpc = env('RPC_URL');
        $token = strtolower($r->token_contract);
        $from = strtolower($r->from_address);
        $receiver = strtolower($r->receiver);
        $amountDecimal = $r->amount;

        // Ensure wallet matches saved one
        $savedWallet = DB::table('customdetails')->where('userid', $r->user_id)->value('wallet_address');
        if (!$savedWallet || strtolower($savedWallet) !== $from) {
            return response()->json(['ok'=>false,'error'=>'Saved wallet mismatch'], 400);
        }

        // Fetch tx receipt
        $receiptResp = Http::post($rpc, [
            'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_getTransactionReceipt','params'=>[$r->tx_hash]
        ]);
        $receipt = $receiptResp->json('result');
        if (!$receipt) return response()->json(['ok'=>false,'error'=>'Receipt not found'], 404);
        if (hexdec($receipt['status'] ?? '0x0') !== 1) {
            return response()->json(['ok'=>false,'error'=>'Transaction failed'], 400);
        }

        // ERC20 Transfer event topic
        $transferTopic = '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef';

        $found = false;
        foreach ($receipt['logs'] as $log) {
            if (strtolower($log['address']) !== $token) continue;
            if ($log['topics'][0] !== $transferTopic) continue;

            $topicFrom = '0x' . substr($log['topics'][1], 26);
            $topicTo   = '0x' . substr($log['topics'][2], 26);

            if (strtolower($topicFrom) !== $from) continue;
            if (strtolower($topicTo) !== $receiver) continue;

            $valueWeiStr = $this->hexToDecString($log['data']);
            $decimals = $this->getTokenDecimals($token, $rpc);
            $requiredWeiStr = $this->decimalToWeiString($amountDecimal, $decimals);

            if (bccomp($valueWeiStr, $requiredWeiStr) >= 0) {
                $found = true;
                break;
            }
        }

        if (!$found) return response()->json(['ok'=>false,'error'=>'No matching Transfer found'], 400);

        return response()->json(['ok'=>true,'tx_hash'=>$r->tx_hash]);
    }

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
