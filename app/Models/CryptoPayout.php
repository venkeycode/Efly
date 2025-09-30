<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPayout extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'chain',
        'from',
        'to',
        'amount',
        'amount_wei',
        'tx_hash',
        'status',
        'reference',
    ];
}
