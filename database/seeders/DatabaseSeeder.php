<?php

namespace Database\Seeders;

use App\Models\BloodInventory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' =>'Edimon A. O',
            'email' => 'eabimon@gmail.com',
            'phone' => '0701583807',
            'avatar' => null,
            'is_verified' => true,
            'role' => 'Superadmin',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' =>  Hash::make('Admin@1234'),
        ]);
        BloodInventory::factory(19)->create();
        $this->call(\Database\Seeders\RelevantTablesSeeder::class);
    }
}
