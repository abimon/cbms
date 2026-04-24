<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagTimeline extends Model
{
    protected $fillable =[
        'bag_id',
        'user_id',
        'description'
    ];
}
