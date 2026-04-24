<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodBank;

class BloodBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'name' => 'Central Blood Bank',
                'location' => '123 Main St, City Center',
                'contact_phone' => '123-456-7890',
                'email' => 'central@bloodbank.com'
            ],
            [
                'name' => 'Regional Blood Bank',
                'location' => '456 Elm St, Suburb',
                'contact_phone' => '987-654-3210',
                'email' => 'regional@bloodbank.com'
            ],
        ];

        foreach ($banks as $bank) {
            BloodBank::create($bank);
        }
    }
}
