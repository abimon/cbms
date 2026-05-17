<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $fillable = ['name', 'location', 'contact_phone', 'email','threshold'];

    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }
}
