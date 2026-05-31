<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $fillable = ['name', 'location', 'contact_phone', 'email','threshold'];

    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class,'collection_agency','name');
    }
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class,'donor_hospital','name');
    }
    public function inventories()
    {
        return $this->hasMany(BloodInventory::class,'collection_agency','name');
    }
    public function users()
    {
        return $this->hasMany(User_bank::class, 'bank_id', 'id');
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'bank_id', 'id');
    }
    public function requests()
    {
        return $this->hasMany(BloodRequest::class, 'donor_hospital', 'name');
    }
}
