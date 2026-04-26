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
    public function bloodbag(){
        return $this->belongsTo(BloodInventory::class,'bloodbag_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function bank(){
        return $this->belongsTo(BloodBank::class,'bank_id');
    }
}
