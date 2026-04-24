<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $fillable = ['name', 'location', 'contact_phone', 'email'];

    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }
}
