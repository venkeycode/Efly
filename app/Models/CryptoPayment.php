<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPayment extends Model
{
    protected $fillable = [
        'user_id',
        'token_contract',
        'from_address',
        'receiver',
        'tx_hash',
        'amount',
        'status'
    ];
}
