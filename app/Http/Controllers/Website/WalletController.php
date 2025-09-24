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
    $chainDecimals = 18;

    $token = $request->query('token');       // optional token contract address
    $decimalsQuery = $request->query('decimals');

    // --- Select RPC URL based on token (or default to Polygon) ---
    $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com'); // fallback
    $symbol = 'MATIC';

    if ($token) {
        $t = strtolower($token);

        // USDT on Polygon
        if ($t === strtolower("0xc2132D05D31c914a87C6611C10748AEb04B58e8F")) {
            $rpcUrl = env('RPC_POLYGON', 'https://polygon-rpc.com');
            $symbol = 'MATIC';
        }

        // USDT on BSC
        if ($t === strtolower("0x55d398326f99059fF775485246999027B3197955")) {
            $rpcUrl = env('RPC_BSC', 'https://bsc-dataseed.binance.org/');
            $symbol = 'BNB';
        }

        // USDT on Ethereum
        if ($t === strtolower("0xdAC17F958D2ee523a2206206994597C13D831ec7")) {
            $rpcUrl = env('RPC_ETH', 'https://mainnet.infura.io/v3/YOUR_INFURA_KEY');
            $symbol = 'ETH';
        }
    }

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
            'symbol' => $symbol,
        ],
    ];

    // --- ERC20 token balance (e.g., USDT) ---
    if ($token) {
        $token = strtolower($token);

        // 1) decimals
        if ($decimalsQuery !== null && is_numeric($decimalsQuery)) {
            $decimals = intval($decimalsQuery);
        } elseif ($token === strtolower("0xc2132D05D31c914a87C6611C10748AEb04B58e8F")) {
            $decimals = 6; // Polygon USDT
        } elseif ($token === strtolower("0xdAC17F958D2ee523a2206206994597C13D831ec7")) {
            $decimals = 6; // Ethereum USDT
        } elseif ($token === strtolower("0x55d398326f99059fF775485246999027B3197955")) {
            $decimals = 18; // BSC USDT
        } else {
            // fallback call decimals()
            $data = '0x313ce567';
            $decResp = Http::post($rpcUrl, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'eth_call',
                'params' => [[ 'to' => $token, 'data' => $data ], 'latest'],
            ]);
            $decimals = $decResp->json('result') ? hexdec($decResp->json('result')) : 18;
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

    // public function showPayPage(Request $r)
    // {
    //     $userId = $r->query('user_id');
    //     $amount = $r->query('amount');
    //     $return = $r->query('return');
    //     $savedWallet = DB::table('customdetails')->where('uid', $userId)->value('wallet_address');
    //     return view('website.pay', compact('userId','amount','return','savedWallet'));
    // }
public function showPayPage(Request $r)
{
    $userId = $r->query('user_id');
    $amount = $r->query('amount');
    $plan_id = $r->query('plan_id');
    $return = 'https://earnnfly.com/New/user/index.php/home';//$r->query('return');

    $savedWallet = DB::table('customdetails')
        ->where('uid', $userId)
        ->value('wallet_address');

    $token = env('USDT_CONTRACT');        // e.g. Polygon USDT contract
    $receiver ='0x8Ab8a499Ca0Ae83A62Aa93397CA9fbDC7Cec9e5A';// env('RECEIVER_WALLET');   // your receiver wallet

    return view('website.pay', compact('userId','amount','return','savedWallet','token','receiver','plan_id'));
}
    /**
     * ✅ Verify ERC20 (USDT) payment
     */
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

    $rpc = env('RPC_URL', 'https://polygon-rpc.com');
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
    if (!$tx) return response()->json(['ok'=>false,'error'=>'Transaction not found'], 404);

    // 2. Verify tx.to == token contract
    if (strtolower($tx['to'] ?? '') !== $token) {
        return response()->json(['ok'=>false,'error'=>'Transaction not sent to token contract'], 400);
    }

    // 3. Decode input data: transfer(receiver, amount)
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

    // 4. Verify amount (convert to decimals if needed)
    $decimals = 6; // USDT on Polygon
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

    return response()->json(['ok'=>true,'tx_hash'=>$r->tx_hash]);
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
