<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAmount extends Model
{
    protected $table = 'requested_amts';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
