<?php

namespace Database\Seeders;

use App\Models\BagTimeline;
use App\Models\BloodBank;
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
            'name' => 'Edimon A. O',
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
        foreach(BloodInventory::all() as $bag){
            if ($bag->status == 'available') {
                BagTimeline::create([
                    'bag_id' => $bag->id,
                    'user_id' => 1,
                    'description' => 'Blood bag received.',
                ]);
                BagTimeline::create([
                    'bag_id' => $bag->id,
                    'user_id' => 1,
                    'description' => 'Blood bag completed testing.',
                ]);
            } else {
                BagTimeline::create([
                    'bag_id' => $bag->id,
                    'user_id' => 1,
                    'description' => 'Blood bag received.',
                ]);
                BagTimeline::create([
                    'bag_id' => $bag->id,
                    'user_id' => 1,
                    'description' => 'Blood bag completed testing.',
                ]);
                BagTimeline::create([
                    'bag_id' => $bag->id,
                    'user_id' => 1,
                    'description' => 'Blood bag ' . $bag->status . '.',
                ]);
            }
        }
        $this->call(\Database\Seeders\RelevantTablesSeeder::class);
        foreach (BloodBank::all() as $key => $bank) {

            for ($i = 0; $i < 30; $i++) {
                $bag = BloodInventory::create([
                    'din' => 'DIN-KE-26-' . ($key < 10 ? '0' . ($key + 1) : ($key + 1)) . '-' . ($i < 10 ? '0' . ($i + 1) : ($i + 1)),
                    'type' => fake()->randomElement(['Whole Blood', 'Platelet', 'Plasma']),
                    'volume' => fake()->randomFloat(1, 1, 3),
                    'blood_type' => fake()->randomElement(['A', 'B', 'AB', 'O']),
                    'rhesus' => fake()->randomElement(['Positive', 'Negative']),
                    'date_collected' => fake()->dateTimeBetween('-3 month', 'now'),
                    'location' => fake()->city(),
                    'collection_agency' => $bank->name,
                    'HIV' => fake()->randomElement(['Positive', 'Negative']),
                    'HBV' => fake()->randomElement(['Positive', 'Negative']),
                    'HCV' => fake()->randomElement(['Positive', 'Negative']),
                    'Syphilis' => fake()->randomElement(['Positive', 'Negative']),
                    'Malaria' => fake()->randomElement(['Positive', 'Negative']),
                    'expiry_date' => fake()->dateTimeBetween('+2 months', '+5 months'),
                    'release_date' => fake()->dateTimeBetween('-5 months', 'now'),
                    'status' => fake()->randomElement(['available', 'used', 'withdrawn']),
                ]);
                if ($bag->status == 'available') {
                    BagTimeline::create([
                        'bag_id' => $bag->id,
                        'user_id' => 1,
                        'description' => 'Blood bag received.',
                    ]);
                    BagTimeline::create([
                        'bag_id' => $bag->id,
                        'user_id' => 1,
                        'description' => 'Blood bag completed testing.',
                    ]);
                } else {
                    BagTimeline::create([
                        'bag_id' => $bag->id,
                        'user_id' => 1,
                        'description' => 'Blood bag received.',
                    ]);
                    BagTimeline::create([
                        'bag_id' => $bag->id,
                        'user_id' => 1,
                        'description' => 'Blood bag completed testing.',
                    ]);
                    BagTimeline::create([
                        'bag_id' => $bag->id,
                        'user_id' => 1,
                        'description' => 'Blood bag '.$bag->status.'.',
                    ]);

                }
            }
        }
    }
}
