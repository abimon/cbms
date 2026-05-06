<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelayStatus extends Model
{
    protected $table = 'relay_statuses';
    protected $fillable = [
        'board_id',
        'relay_id',
        'status',
        'isDone',
        'code',
    ];
}
