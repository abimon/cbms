<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = ['blood_type', 'quantity', 'hospital', 'contact_phone', 'reason', 'status', 'request_date'];

    
}
