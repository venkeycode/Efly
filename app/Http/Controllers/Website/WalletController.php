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

}
