<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodStorage extends Model
{
    protected $fillable =[
        'bloodbag_id',
        'bank_id',
        'status'
    ];
    
    public function blood()
    {
        return $this->belongsTo(BloodInventory::class);
    }

    public function bank()
    {
        return $this->belongsTo(BloodBank::class);
    }
}
