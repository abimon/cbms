<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_bank extends Model
{
    protected $fillable = [
        'user_id','bank_id','status'
    ];


    public function bank(){
        return $this->belongsTo(BloodBank::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
