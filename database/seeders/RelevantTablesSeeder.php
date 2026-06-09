<?php

namespace Database\Seeders;

use App\Models\BloodBank;
use App\Models\BloodInventory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RelevantTablesSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = \Faker\Factory::create('en_K');
        $faker->seed(1234);

        Schema::disableForeignKeyConstraints();

        foreach ([
            'activities',
            'bag_timelines',
            'withdrawals',
            'blood_storages',
            'user_banks',
            'blood_banks',
            'relay_statuses',
            'blood_requests',
        ] as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        User::factory(100)->create();
        
        $userIds = User::pluck('id')->all();

        $bankRows = [];
        for ($i = 0; $i < 100; $i++) {
            $threshold = [];
            foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group) {
                array_push($threshold, [
                    'blood_group' => $group,
                    'threshold' => $faker->numberBetween(51, 100),
                ]);
            }
            $bankRows[] = [
                'name' => $faker->randomElement(BloodInventory::pluck('collection_agency')->unique()->all()),
                'location' => $faker->address(),
                'contact_phone' => $faker->unique()->e164PhoneNumber(),
                'email' => $faker->unique()->safeEmail(),
                'threshold' => json_encode($threshold),
                'status' => $faker->randomElement(['allowed', 'pending', 'rejected']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('blood_banks')->insert($bankRows);
        $bankIds = DB::table('blood_banks')->pluck('id')->all();
        $inventoryIds = BloodInventory::pluck('id')->all();

        $this->seedUserBanks($faker, $userIds, $bankIds);
        $this->seedBloodStorages($faker, $inventoryIds, $bankIds);
        $this->seedWithdrawals($faker, $userIds, $inventoryIds, $bankIds);
        $this->seedBagTimelines($faker, $userIds, $inventoryIds);
        $this->seedActivities($faker, $userIds);
        $this->seedRelayStatuses($faker);
        $this->seedBloodRequests($faker);
    }

    private function seedUserBanks($faker, array $userIds, array $bankIds): void
    {
        $userBankRows = [];
        $pairs = [];

        while (count($userBankRows) < 100) {
            $userId = Arr::random($userIds);
            $bankId = Arr::random($bankIds);
            $key = "$userId-$bankId";

            if (isset($pairs[$key])) {
                continue;
            }

            $pairs[$key] = true;
            $userBankRows[] = [
                'user_id' => $userId,
                'bank_id' => $bankId,
                'role' => $faker->randomElement(['agent', 'admin', 'staff']),
                'status' => $faker->randomElement(['approved', 'rejected', 'pending']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_banks')->insert($userBankRows);
    }

    private function seedBloodStorages($faker, array $inventoryIds, array $bankIds): void
    {
        $storageRows = [];
        $pairs = [];

        while (count($storageRows) < 100) {
            $bloodbagId = Arr::random($inventoryIds);
            $bankId = Arr::random($bankIds);
            $key = "$bloodbagId-$bankId";

            if (isset($pairs[$key])) {
                continue;
            }

            $pairs[$key] = true;
            $storageRows[] = [
                'bloodbag_id' => $bloodbagId,
                'bank_id' => $bankId,
                'status' => $faker->randomElement(['available', 'requested', 'used', 'expired', 'withdrawn']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('blood_storages')->insert($storageRows);
    }

    private function seedWithdrawals($faker, array $userIds, array $inventoryIds, array $bankIds): void
    {
        $withdrawalRows = [];
        $triples = [];

        while (count($withdrawalRows) < 100) {
            $userId = Arr::random($userIds);
            $bloodbagId = Arr::random($inventoryIds);
            $bankId = Arr::random($bankIds);
            $key = "$userId-$bloodbagId-$bankId";

            if (isset($triples[$key])) {
                continue;
            }

            $triples[$key] = true;
            $withdrawalRows[] = [
                'user_id' => $userId,
                'bloodbag_id' => $bloodbagId,
                'bank_id' => $bankId,
                'purpose' => $faker->randomElement(['testing', 'transfer', 'disposal']),
                'status' => 'withdrawn',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('withdrawals')->insert($withdrawalRows);
    }

    private function seedBagTimelines($faker, array $userIds, array $inventoryIds): void
    {
        $timelineRows = [];
        $pairs = [];

        while (count($timelineRows) < 100) {
            $bagId = Arr::random($inventoryIds);
            $userId = Arr::random($userIds);
            $key = "$bagId-$userId";

            if (isset($pairs[$key])) {
                continue;
            }

            $pairs[$key] = true;
            $timelineRows[] = [
                'bag_id' => $bagId,
                'user_id' => $userId,
                'description' => $faker->unique()->sentence(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('bag_timelines')->insert($timelineRows);
    }

    private function seedActivities($faker, array $userIds): void
    {
        $activityRows = [];

        for ($i = 0; $i < 1000; $i++) {
            $activityRows[] = [
                'user_id' => Arr::random($userIds),
                'description' => $faker->sentence(8),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('activities')->insert($activityRows);
    }

    private function seedRelayStatuses($faker): void
    {
        $relayRows = [];

        for ($i = 0; $i < 100; $i++) {
            $relayRows[] = [
                'board_id' => $faker->unique()->bothify('BOARD-###-??'),
                'relay_id' => $faker->unique()->bothify('RELAY-##'),
                'status' => $faker->randomElement(['on', 'off', 'fault', 'idle']),
                'isDone' => $faker->boolean(50),
                'code' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('relay_statuses')->insert($relayRows);
    }

    private function seedBloodRequests($faker): void
    {
        $requestRows = [];

        for ($i = 0; $i < 100; $i++) {
            $requestRows[] = [
                'request_type' => $faker->randomElement(['component', 'whole_blood']),
                'blood_type' => $faker->randomElement(['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+']),
                'quantity' => $faker->numberBetween(1, 20),
                'donor_hospital' => $faker->company() . ' Laboratory',
                'recepient_hospital' => $faker->company() . ' Medical Center',
                'contact_phone' => $faker->unique()->e164PhoneNumber(),
                'reason' => $faker->unique()->sentence(12),
                'status' => $faker->randomElement(['pending', 'approved', 'fulfilled', 'rejected']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('blood_requests')->insert($requestRows);
    }
}
