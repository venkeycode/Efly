<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\State;

class Customer extends Authenticatable
{
    protected $table = 'customdetails';
    public $timestamps = false;
    protected $primaryKey = 'uid';
    protected $hidden = [
        'password',
        'remember_token',
    ];



}
