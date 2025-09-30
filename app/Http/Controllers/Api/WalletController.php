<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use App\Models\CryptoPayment;
use App\Models\CryptoPayout;
use Validator;
use App\Models\RequestAmount;

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
public function approveSingle(Request $request, $id)
{
    // Accept JSON payload from JS or regular form submit
    $isJson = $request->expectsJson() || $request->isJson() || $request->header('Accept') === 'application/json';

    // Validate incoming payload keys (matching what your UI sends)
    $validator = Validator::make($request->all(), [
        'withdraw_id'    => 'required',               // we still accept $id path param, but validate payload too
        'admin_address'  => 'required|string',
        'to_address'     => 'required|string',
        'amount'         => 'required',               // accept decimal string
        'token_contract' => 'required|string',
        'tx_hash'        => 'nullable|string',
        'chain'          => 'nullable|string',        // optional: 'bsc', 'polygon', 'eth'
    ]);

    if ($validator->fails()) {
        if ($isJson) {
            return response()->json(['ok' => false, 'error' => 'validation', 'details' => $validator->errors()], 422);
        }
        return back()->withErrors($validator)->withInput();
    }

    // Normalize payload
    $payloadWithdrawId = $request->input('withdraw_id', $id);
    $adminAddress = strtolower($request->input('admin_address'));
    $toAddress    = strtolower($request->input('to_address'));
    $token        = strtolower($request->input('token_contract'));
    $amountDecimal = (string)$request->input('amount');
    $txHash = $request->input('tx_hash', null);
    $chain = strtolower($request->input('chain', 'bsc'));

    // Pick RPC by chain
    $rpc = match ($chain) {
        'polygon' => env('RPC_POLYGON', env('RPC_URL', 'https://polygon-rpc.com')),
        'eth', 'ethereum' => env('RPC_ETH', env('RPC_URL', 'https://mainnet.infura.io/v3/YOUR_KEY')),
        default => env('RPC_BSC', env('RPC_URL', 'https://bsc-dataseed.binance.org/')),
    };

    DB::beginTransaction();
    try {
        // Determine token decimals (fallback to common defaults if call fails)
        try {
            $decimals = $this->getTokenDecimals($token, $rpc);
            if (!is_int($decimals)) $decimals = intval($decimals);
        } catch (\Throwable $e) {
            // fallback: USDT common defaults
            if (in_array($token, [strtolower(env('USDT_CONTRACT_BSC', '0x55d398326f99059fF775485246999027B3197955'))])) {
                $decimals = 18; // some BSC tokens use 18
            } else {
                $decimals = 18;
            }
        }

        // Convert requested amount into smallest unit string
        $amountWeiStr = $this->decimalToWeiString($amountDecimal, $decimals);

        // Create payout record (adjust model/fields to your schema)
        $payout = \App\Models\CryptoPayout::create([
            'user_id'     => $request->user_id ?? 0,
            'token'       => $token,
            'chain'       => $chain,
            'from'        => $adminAddress ?: strtolower(env('ADMIN_ADDRESS', '')),
            'to'          => $toAddress,
            'amount'      => $amountDecimal,
            'amount_wei'  => $amountWeiStr,
            'tx_hash'     => $txHash,
            'status'      => $txHash ? 'pending' : 'pending',
            'reference'   => $request->input('reference', null),
        ]);

        // If tx_hash present, try to verify it on-chain now:
        $verificationResult = null;
        if ($txHash) {
            // 1) get tx by hash
            $txResp = Http::post($rpc, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'eth_getTransactionByHash',
                'params' => [$txHash],
            ]);
            $tx = $txResp->json('result');

            if ($tx) {
                // If tx.to equals token contract and input begins with transfer selector
                $ok = false;
                $status = 'pending';

                // If contract transfer (ERC20)
                $input = $tx['input'] ?? '';
                if (substr($input, 0, 10) === '0xa9059cbb') {
                    // parse recipient and value from data
                    $toFromInput = '0x' . substr($input, 34, 40);
                    // value hex (32 bytes)
                    $valueHex = '0x' . ltrim(substr($input, 74), '0');
                    if ($valueHex === '0x') $valueHex = '0x0';
                    $valueDec = $this->hexToDecString($valueHex);

                    // compare addresses (tx.to is token contract)
                    if (strtolower($toFromInput) === $toAddress && strtolower($tx['to'] ?? '') === $token) {
                        // amount check: valueDec >= expected
                        if (bccomp($valueDec, $amountWeiStr) >= 0) {
                            // check receipt status
                            $receiptResp = Http::post($rpc, [
                                'jsonrpc' => '2.0',
                                'id' => 2,
                                'method' => 'eth_getTransactionReceipt',
                                'params' => [$txHash],
                            ]);
                            $receipt = $receiptResp->json('result');
                            if ($receipt && isset($receipt['status'])) {
                                $statusInt = hexdec($receipt['status']);
                                if ($statusInt === 1) {
                                    $status = 'confirmed';
                                    $ok = true;
                                } else {
                                    $status = 'failed';
                                    $ok = false;
                                }
                            } else {
                                $status = 'pending';
                                $ok = true; // tx exists and amounts match, but not yet mined/confirmed
                            }
                        } else {
                            $ok = false;
                            $status = 'amount_mismatch';
                        }
                    } else {
                        $ok = false;
                        $status = 'recipient_or_contract_mismatch';
                    }
                } else {
                    // Maybe this is a native transfer to receiver (unlikely for ERC20 withdraws)
                    if (strtolower($tx['to'] ?? '') === $toAddress) {
                        // value is native (wei)
                        $valueDec = $this->hexToDecString($tx['value'] ?? '0x0');
                        // convert expected amountDecimal to wei of native chain? skipping — assume mismatch
                        $ok = false;
                        $status = 'not_erc20_transfer';
                    } else {
                        $ok = false;
                        $status = 'unrecognized_input';
                    }
                }

                // Update payout record according to verification result
                if ($ok && $status === 'confirmed') {
                    $payout->status = 'confirmed';
                    $payout->tx_hash = $txHash;
                    $payout->save();
                    $verificationResult = ['ok' => true, 'status' => 'confirmed'];
                } elseif ($ok && $status === 'pending') {
                    $payout->status = 'pending';
                    $payout->tx_hash = $txHash;
                    $payout->save();
                    $verificationResult = ['ok' => true, 'status' => 'pending'];
                } else {
                    $payout->status = 'failed';
                    $payout->tx_hash = $txHash;
                    $payout->save();
                    $verificationResult = ['ok' => false, 'status' => $status];
                }
            } else {
                // tx not found
                $payout->status = 'pending';
                $payout->tx_hash = $txHash;
                $payout->save();
                $verificationResult = ['ok' => false, 'status' => 'tx_not_found'];
            }
        }

        // Update RequestAmount record in CI DB if desired
        $req = RequestAmount::find($payloadWithdrawId);
        if ($req) {
            $req->status = 1; // processed — adapt per your schema
            $req->paid_date = now();
            if (property_exists($req, 'usdt_address')) $req->usdt_address = $toAddress;
            if (property_exists($req, 'paid_amount')) $req->paid_amount = $amountDecimal;
            // maybe store tx hash
            if (property_exists($req, 'tx_hash')) $req->tx_hash = $txHash;
            $req->save();
        }

        DB::commit();

        if ($isJson) {
            return response()->json([
                'ok' => true,
                'payout_id' => $payout->id,
                'verify' => $verificationResult
            ]);
        }

        return redirect()->route('admin.withdraw.single', ['id' => $payloadWithdrawId])
                         ->with('success', 'Withdraw recorded (payout id: ' . $payout->id . ').');

    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error("approveSingle failed: " . $e->getMessage(), ['exception' => $e, 'payload' => $request->all()]);

        if ($isJson) {
            return response()->json(['ok' => false, 'error' => 'server_error', 'message' => $e->getMessage()], 500);
        }
        return back()->with('error', 'Failed to record withdraw: ' . $e->getMessage());
    }
}
// public function approveSingle(Request $request, $id)
// {
//     // Accept JSON payload from JS or regular form submit
//     $isJson = $request->expectsJson() || $request->isJson() || $request->header('Accept') === 'application/json';

//     // Validate incoming payload keys (matching what your UI sends)
//     $validator = Validator::make($request->all(), [
//         'withdraw_id'    => 'required',               // we still accept $id path param, but validate payload too
//         'admin_address'  => 'required|string',
//         'to_address'     => 'required|string',
//         'amount'         => 'required',               // accept decimal string
//         'token_contract' => 'required|string',
//         'tx_hash'        => 'nullable|string',
//         'chain'          => 'nullable|string',        // optional: 'bsc', 'polygon', 'eth'
//     ]);

//     if ($validator->fails()) {
//         if ($isJson) {
//             return response()->json(['ok' => false, 'error' => 'validation', 'details' => $validator->errors()], 422);
//         }
//         return back()->withErrors($validator)->withInput();
//     }

//     // Normalize payload
//     $payloadWithdrawId = $request->input('withdraw_id', $id);
//     $adminAddress = strtolower($request->input('admin_address'));
//     $toAddress    = strtolower($request->input('to_address'));
//     $token        = strtolower($request->input('token_contract'));
//     $amountDecimal = (string)$request->input('amount');
//     $txHash = $request->input('tx_hash', null);
//     $chain = strtolower($request->input('chain', 'bsc'));

//     // Pick RPC by chain
//     $rpc = match ($chain) {
//         'polygon' => env('RPC_POLYGON', env('RPC_URL', 'https://polygon-rpc.com')),
//         'eth', 'ethereum' => env('RPC_ETH', env('RPC_URL', 'https://mainnet.infura.io/v3/YOUR_KEY')),
//         default => env('RPC_BSC', env('RPC_URL', 'https://bsc-dataseed.binance.org/')),
//     };

//     DB::beginTransaction();
//     try {
//         // Determine token decimals (fallback to common defaults if call fails)
//         try {
//             $decimals = $this->getTokenDecimals($token, $rpc);
//             if (!is_int($decimals)) $decimals = intval($decimals);
//         } catch (\Throwable $e) {
//             // fallback: USDT common defaults
//             if (in_array($token, [strtolower(env('USDT_CONTRACT_BSC', '0x55d398326f99059fF775485246999027B3197955'))])) {
//                 $decimals = 18; // some BSC tokens use 18
//             } else {
//                 $decimals = 18;
//             }
//         }

//         // Convert requested amount into smallest unit string
//         $amountWeiStr = $this->decimalToWeiString($amountDecimal, $decimals);

//         // Create payout record (adjust model/fields to your schema)
//         $payout = \App\Models\CryptoPayout::create([
//             'user_id'     => $request->user_id ?? 0,
//             'token'       => $token,
//             'chain'       => $chain,
//             'from'        => $adminAddress ?: strtolower(env('ADMIN_ADDRESS', '')),
//             'to'          => $toAddress,
//             'amount'      => $amountDecimal,
//             'amount_wei'  => $amountWeiStr,
//             'tx_hash'     => $txHash,
//             'status'      => $txHash ? 'pending' : 'pending',
//             'reference'   => $request->input('reference', null),
//         ]);

//         // If tx_hash present, try to verify it on-chain now:
//         $verificationResult = null;
//         if ($txHash) {
//             // 1) get tx by hash
//             $txResp = Http::post($rpc, [
//                 'jsonrpc' => '2.0',
//                 'id' => 1,
//                 'method' => 'eth_getTransactionByHash',
//                 'params' => [$txHash],
//             ]);
//             $tx = $txResp->json('result');

//             if ($tx) {
//                 // If tx.to equals token contract and input begins with transfer selector
//                 $ok = false;
//                 $status = 'pending';

//                 // If contract transfer (ERC20)
//                 $input = $tx['input'] ?? '';
//                 if (substr($input, 0, 10) === '0xa9059cbb') {
//                     // parse recipient and value from data
//                     $toFromInput = '0x' . substr($input, 34, 40);
//                     // value hex (32 bytes)
//                     $valueHex = '0x' . ltrim(substr($input, 74), '0');
//                     if ($valueHex === '0x') $valueHex = '0x0';
//                     $valueDec = $this->hexToDecString($valueHex);

//                     // compare addresses (tx.to is token contract)
//                     if (strtolower($toFromInput) === $toAddress && strtolower($tx['to'] ?? '') === $token) {
//                         // amount check: valueDec >= expected
//                         if (bccomp($valueDec, $amountWeiStr) >= 0) {
//                             // check receipt status
//                             $receiptResp = Http::post($rpc, [
//                                 'jsonrpc' => '2.0',
//                                 'id' => 2,
//                                 'method' => 'eth_getTransactionReceipt',
//                                 'params' => [$txHash],
//                             ]);
//                             $receipt = $receiptResp->json('result');
//                             if ($receipt && isset($receipt['status'])) {
//                                 $statusInt = hexdec($receipt['status']);
//                                 if ($statusInt === 1) {
//                                     $status = 'confirmed';
//                                     $ok = true;
//                                 } else {
//                                     $status = 'failed';
//                                     $ok = false;
//                                 }
//                             } else {
//                                 $status = 'pending';
//                                 $ok = true; // tx exists and amounts match, but not yet mined/confirmed
//                             }
//                         } else {
//                             $ok = false;
//                             $status = 'amount_mismatch';
//                         }
//                     } else {
//                         $ok = false;
//                         $status = 'recipient_or_contract_mismatch';
//                     }
//                 } else {
//                     // Maybe this is a native transfer to receiver (unlikely for ERC20 withdraws)
//                     if (strtolower($tx['to'] ?? '') === $toAddress) {
//                         // value is native (wei)
//                         $valueDec = $this->hexToDecString($tx['value'] ?? '0x0');
//                         // convert expected amountDecimal to wei of native chain? skipping — assume mismatch
//                         $ok = false;
//                         $status = 'not_erc20_transfer';
//                     } else {
//                         $ok = false;
//                         $status = 'unrecognized_input';
//                     }
//                 }

//                 // Update payout record according to verification result
//                 if ($ok && $status === 'confirmed') {
//                     $payout->status = 'confirmed';
//                     $payout->tx_hash = $txHash;
//                     $payout->save();
//                     $verificationResult = ['ok' => true, 'status' => 'confirmed'];
//                 } elseif ($ok && $status === 'pending') {
//                     $payout->status = 'pending';
//                     $payout->tx_hash = $txHash;
//                     $payout->save();
//                     $verificationResult = ['ok' => true, 'status' => 'pending'];
//                 } else {
//                     $payout->status = 'failed';
//                     $payout->tx_hash = $txHash;
//                     $payout->save();
//                     $verificationResult = ['ok' => false, 'status' => $status];
//                 }
//             } else {
//                 // tx not found
//                 $payout->status = 'pending';
//                 $payout->tx_hash = $txHash;
//                 $payout->save();
//                 $verificationResult = ['ok' => false, 'status' => 'tx_not_found'];
//             }
//         }

//         // Update RequestAmount record in CI DB if desired
//         $req = RequestAmount::find($payloadWithdrawId);
//         if ($req) {
//             $req->status = 1; // processed — adapt per your schema
//             $req->paid_date = now();
//             if (property_exists($req, 'usdt_address')) $req->usdt_address = $toAddress;
//             if (property_exists($req, 'paid_amount')) $req->paid_amount = $amountDecimal;
//             // maybe store tx hash
//             if (property_exists($req, 'tx_hash')) $req->tx_hash = $txHash;
//             $req->save();
//         }

//         DB::commit();

//         if ($isJson) {
//             return response()->json([
//                 'ok' => true,
//                 'payout_id' => $payout->id,
//                 'verify' => $verificationResult
//             ]);
//         }

//         return redirect()->route('admin.withdraw.single', ['id' => $payloadWithdrawId])
//                          ->with('success', 'Withdraw recorded (payout id: ' . $payout->id . ').');

//     } catch (\Throwable $e) {
//         DB::rollBack();
//         \Log::error("approveSingle failed: " . $e->getMessage(), ['exception' => $e, 'payload' => $request->all()]);

//         if ($isJson) {
//             return response()->json(['ok' => false, 'error' => 'server_error', 'message' => $e->getMessage()], 500);
//         }
//         return back()->with('error', 'Failed to record withdraw: ' . $e->getMessage());
//     }
// }
}
