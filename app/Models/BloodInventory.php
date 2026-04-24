<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'din',
        'type',
        'volume',
        'blood_type',
        'rhesus',
        'date_collected',
        'location',
        'collection_agency',
        'HIV',
        'HBV',
        'HCV',
        'Syphilis',
        'Malaria',
        'expiry_date',
        'release_date',
        'status',
        ];

    public function timelines(){
        return $this->hasMany(BagTimeline::class, 'bag_id','id');
    }
    /**
     * Get a JSON payload suitable for encoding in a QR code
     */
    public function getQrPayloadAttribute()
    {
        return json_encode([
            'din'=>$this->din,
            'type'=>$this->type,
            'volume'=>$this->volume,
            'blood_type'=>$this->blood_type,
            'rhesus'=>$this->rhesus,
            'date_collected'=>$this->date_collected,
            'location'=>$this->location,
            'collection_agency'=>$this->collection_agency,
            'HIV'=>$this->HIV,
            'HBV'=>$this->HBV,
            'HCV'=>$this->HCV,
            'Syphilis'=>$this->Syphilis,
            'Malaria'=>$this->Malaria
        ]);
    }
}
