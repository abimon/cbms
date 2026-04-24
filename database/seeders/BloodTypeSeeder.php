<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = [
            ['name' => 'A+'],
            ['name' => 'A-'],
            ['name' => 'B+'],
            ['name' => 'B-'],
            ['name' => 'AB+'],
            ['name' => 'AB-'],
            ['name' => 'O+'],
            ['name' => 'O-'],
        ];

        foreach ($bloodTypes as $type) {
            BloodType::create($type);
        }
    }
}
