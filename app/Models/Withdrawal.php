<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable =[
        'bloodbag_id',
        'user_id',
        'bank_id',
        'status'
    ];
}
