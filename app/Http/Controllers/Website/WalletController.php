<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function show()
    {
        $customer = Auth::user(); // or Auth::guard('customer')->user()
        return view('website.wallet', compact('customer'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required|string'
        ]);

        // if you use a different guard:
        // $customer = Auth::guard('customer')->user();
        $customer = Auth::user();
        if (! $customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $customer->wallet_address = $request->input('wallet_address');
        $customer->save();

        return response()->json(['ok' => true, 'wallet_address' => $customer->wallet_address]);
    }
}
