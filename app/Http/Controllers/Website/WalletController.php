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
        $userId = $request->get('user_id'); // passed from CodeIgniter
        return view('website.connect', compact('userId'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        // since both apps share DB, just update user row
        $customer = Customer::find($request->user_id);
        $customer->wallet_address = $request->wallet_address;
        $customer->save();
        // DB::table('users')
        //     ->where('id', $request->user_id)
        //     ->update(['wallet_address' => $request->wallet_address]);

        // after saving, redirect back to CI app
        return response()->json([
            'ok' => true,
            'wallet_address' => $request->wallet_address,
            'redirect' => 'https://earnnfly.com/New/user/index.php/home'
        ]);
    }

    public function getBalance(Request $request, $address)
{
    $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com'); // set in .env
    $chainDecimals = 18;

    $token = $request->query('token');       // optional ERC20 contract address
    $decimalsQuery = $request->query('decimals'); // optional token decimals override

    // 1) Native balance (always return)
    $response = Http::post($rpcUrl, [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => 'eth_getBalance',
        'params' => [$address, 'latest'],
    ]);

    if ($response->failed()) {
        return response()->json(['ok' => false, 'error' => 'RPC call failed', 'details' => $response->body()], 500);
    }

    $nativeHex = $response->json('result') ?? null;
    if (!$nativeHex) {
        return response()->json(['ok' => false, 'error' => 'No native balance returned'], 404);
    }

    $nativeWeiStr = $this->hexToDecString($nativeHex); // string decimal of wei
    $nativeBalance = $this->formatUnits($nativeWeiStr, $chainDecimals); // decimal string

    $out = [
        'ok' => true,
        'address' => $address,
        'native' => [
            'balance_wei' => $nativeWeiStr,    // string (wei)
            'balance' => $nativeBalance,       // human string (18 decimals)
            'symbol' => 'NATIVE',
        ],
    ];

    // 2) If token passed, get token balance and decimals
    if ($token) {
        $token = strtolower($token);
        // get decimals (if provided by query use it, otherwise call contract)
        if ($decimalsQuery !== null && is_numeric($decimalsQuery)) {
            $decimals = intval($decimalsQuery);
        } else {
            // call decimals() -> method id = 0x313ce567
            $data = '0x313ce567';
            $decResp = Http::post($rpcUrl, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'eth_call',
                'params' => [
                    [
                        'to' => $token,
                        'data' => $data
                    ],
                    'latest'
                ],
            ]);

            $decResult = $decResp->json('result');
            if ($decResult && $decResult !== '0x') {
                // parse small hex to int
                $decimals = hexdec($decResult);
            } else {
                // fallback to 18 if token doesn't implement decimals properly
                $decimals = 18;
            }
        }

        // balanceOf(address) -> method id 0x70a08231 + padded address
        $method = '70a08231';
        $addr = strtolower($address);
        if (substr($addr, 0, 2) === '0x') $addr = substr($addr, 2);
        $addr = str_pad($addr, 64, '0', STR_PAD_LEFT);
        $data = '0x' . $method . $addr;

        $balResp = Http::post($rpcUrl, [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => [
                [
                    'to' => $token,
                    'data' => $data
                ],
                'latest'
            ],
        ]);

        $balResult = $balResp->json('result') ?? null;
        if (!$balResult) {
            $tokenBalanceWeiStr = "0";
            $tokenBalanceFormatted = "0";
        } else {
            $tokenBalanceWeiStr = $this->hexToDecString($balResult);
            $tokenBalanceFormatted = $this->formatUnits($tokenBalanceWeiStr, $decimals);
        }

        $out['token'] = [
            'contract' => $token,
            'decimals' => $decimals,
            'balance_wei' => $tokenBalanceWeiStr,
            'balance' => $tokenBalanceFormatted
        ];
    }

    return response()->json($out);
}

/**
 * Convert hex string like 0x<...> to decimal string using BCMath to avoid overflow.
 * Returns a string representation of the decimal number.
 */
private function hexToDecString($hex)
{
    if (!$hex) return '0';
    if (substr($hex, 0, 2) === '0x') $hex = substr($hex, 2);
    $dec = '0';
    $len = strlen($hex);
    for ($i = 0; $i < $len; $i++) {
        $dec = bcmul($dec, '16', 0);
        $dec = bcadd($dec, hexdec($hex[$i]), 0);
    }
    return $dec; // decimal string
}

/**
 * Convert a decimal string (wei or token smallest unit) to human readable with decimals places.
 * Returns string (not float) to preserve precision.
 */
private function formatUnits(string $weiDecString, int $decimals = 18): string
{
    if ($decimals <= 0) return $weiDecString;
    // If weiDecString shorter than decimals, left-pad with zeros
    $negative = false;
    if (strpos($weiDecString, '-') === 0) {
        $negative = true;
        $weiDecString = substr($weiDecString, 1);
    }
    $len = strlen($weiDecString);
    if ($len <= $decimals) {
        $intPart = '0';
        $fracPart = str_pad($weiDecString, $decimals, '0', STR_PAD_LEFT);
    } else {
        $intPart = substr($weiDecString, 0, $len - $decimals);
        $fracPart = substr($weiDecString, $len - $decimals);
    }
    // trim trailing zeros on fractional part
    $fracPart = rtrim($fracPart, '0');
    $result = $intPart . ($fracPart !== '' ? '.' . $fracPart : '');
    if ($negative) $result = '-' . $result;
    return $result;
}
    // public function getBalance($address)
    // {
    //     $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com'); // fallback if not in .env
    //     $chainDecimals = 18; // ETH, MATIC, BNB all use 18 decimals

    //     // Call JSON-RPC: eth_getBalance
    //     $response = Http::post($rpcUrl, [
    //         'jsonrpc' => '2.0',
    //         'id' => 1,
    //         'method' => 'eth_getBalance',
    //         'params' => [$address, 'latest'],
    //     ]);

    //     if ($response->failed()) {
    //         return response()->json([
    //             'ok' => false,
    //             'error' => 'RPC call failed',
    //             'details' => $response->body(),
    //         ], 500);
    //     }

    //     $result = $response->json('result');
    //     if (!$result) {
    //         return response()->json([
    //             'ok' => false,
    //             'error' => 'No balance returned',
    //         ], 404);
    //     }

    //     // Convert hex balance (wei) to decimal
    //     $balanceWei = hexdec(substr($result, 2)); // hex string to int
    //     $balance = $balanceWei / pow(10, $chainDecimals);

    //     return response()->json([
    //         'ok' => true,
    //         'address' => $address,
    //         'balance_wei' => $balanceWei,
    //         'balance' => $balance, // in ETH/MATIC/BNB
    //     ]);
    // }

     public function showPayPage(Request $r)
    {
        $userId = $r->query('user_id');
        $amount = $r->query('amount');
        $return = $r->query('return'); // CI return URL
        $savedWallet = DB::table('customdetails')->where('uid', $userId)->value('wallet_address');
        return view('website.pay', compact('userId','amount','return','savedWallet'));
    }

    // Verify payment (API)
    public function verifyPayment(Request $r)
    {
        $r->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|string',
            'tx_hash' => 'required|string',
            'from_address' => 'required|string',
        ]);

        $userId = $r->user_id;
        $txHash = $r->tx_hash;
        $amount = $r->amount;
        $fromAddress = strtolower($r->from_address);

        $savedWallet = DB::table('customdetails')->where('userid', $userId)->value('wallet_address');
        if (!$savedWallet) return response()->json(['ok'=>false,'error'=>'No saved wallet for user'], 400);

        if (strtolower($savedWallet) !== $fromAddress) {
            return response()->json(['ok'=>false,'error'=>'Sender does not match saved wallet'], 400);
        }

        $receiver = strtolower(env('RECEIVER_WALLET'));
        $rpc = env('RPC_URL');
        if (!$rpc) return response()->json(['ok'=>false,'error'=>'RPC not configured'], 500);

        // get tx
        $txResp = Http::post($rpc, [
            'jsonrpc'=>'2.0','id'=>1,'method'=>'eth_getTransactionByHash','params'=>[$txHash]
        ]);
        $tx = $txResp->json('result');
        if (!$tx) return response()->json(['ok'=>false,'error'=>'Transaction not found'], 404);

        $to = strtolower($tx['to'] ?? '');
        $from = strtolower($tx['from'] ?? '');
        $valueWei = $this->hexToDecString($tx['value'] ?? '0x0');
        $requiredWei = $this->toWeiString($amount);

        if ($to !== $receiver) return response()->json(['ok'=>false,'error'=>'Receiver mismatch'], 400);
        if ($from !== $fromAddress) return response()->json(['ok'=>false,'error'=>'Sender mismatch'], 400);
        if (bccomp($valueWei, $requiredWei) < 0) return response()->json(['ok'=>false,'error'=>'Insufficient amount'], 400);

        // receipt
        $receiptResp = Http::post($rpc, ['jsonrpc'=>'2.0','id'=>2,'method'=>'eth_getTransactionReceipt','params'=>[$txHash]]);
        $receipt = $receiptResp->json('result');
        if (!$receipt || hexdec($receipt['status'] ?? '0x0') !== 1) {
            return response()->json(['ok'=>false,'error'=>'Transaction not successful or receipt missing'], 400);
        }

        // success
        return response()->json(['ok'=>true,'tx_hash'=>$txHash]);
    }

    // private function hexToDecString($hex) {
    //     if (substr($hex,0,2)==='0x') $hex=substr($hex,2);
    //     $dec='0';
    //     for ($i=0;$i<strlen($hex);$i++){
    //         $dec = bcmul($dec,'16',0);
    //         $dec = bcadd($dec, hexdec($hex[$i]), 0);
    //     }
    //     return $dec;
    // }
    private function toWeiString($amount) {
        return bcmul($amount, bcpow('10','18',0), 0);
    }
}
