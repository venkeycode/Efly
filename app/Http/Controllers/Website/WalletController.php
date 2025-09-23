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

    public function getBalance($address)
    {
        $rpcUrl = env('RPC_URL', 'https://polygon-rpc.com'); // fallback if not in .env
        $chainDecimals = 18; // ETH, MATIC, BNB all use 18 decimals

        // Call JSON-RPC: eth_getBalance
        $response = Http::post($rpcUrl, [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getBalance',
            'params' => [$address, 'latest'],
        ]);

        if ($response->failed()) {
            return response()->json([
                'ok' => false,
                'error' => 'RPC call failed',
                'details' => $response->body(),
            ], 500);
        }

        $result = $response->json('result');
        if (!$result) {
            return response()->json([
                'ok' => false,
                'error' => 'No balance returned',
            ], 404);
        }

        // Convert hex balance (wei) to decimal
        $balanceWei = hexdec(substr($result, 2)); // hex string to int
        $balance = $balanceWei / pow(10, $chainDecimals);

        return response()->json([
            'ok' => true,
            'address' => $address,
            'balance_wei' => $balanceWei,
            'balance' => $balance, // in ETH/MATIC/BNB
        ]);
    }

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

    private function hexToDecString($hex) {
        if (substr($hex,0,2)==='0x') $hex=substr($hex,2);
        $dec='0';
        for ($i=0;$i<strlen($hex);$i++){
            $dec = bcmul($dec,'16',0);
            $dec = bcadd($dec, hexdec($hex[$i]), 0);
        }
        return $dec;
    }
    private function toWeiString($amount) {
        return bcmul($amount, bcpow('10','18',0), 0);
    }
}
