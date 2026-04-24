<?php

use App\Models\BloodInventory;
use App\Models\BloodType;
use App\Models\Donor;
use App\Models\BloodBank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

it('creates inventory by merging scan_data payload', function () {
    // prepare related records
    $user = User::factory()->create();
    $this->actingAs($user);

    $type = BloodType::create(['name' => 'A+']);
    $donor = Donor::create([
        'name'=>'Test Donor',
        'email'=>'donor@example.com',
        'phone'=>'1234567890',
        'address'=>'123 Test St',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'blood_type_id' => $type->id
    ]);
    $bank = BloodBank::create(['name' => 'Central Bank','location' => 'City Center','contact_phone'=>'555-1234']);

    $payload = json_encode([
        'blood_type_id' => $type->id,
        'donor_id' => $donor->id,
        'blood_bank_id' => $bank->id,
        'donation_date' => '2026-03-05',
        'expiry_date' => '2026-04-05',
        'quantity' => 3,
        'status' => 'available',
    ]);

    $response = $this->post(route('blood-inventories.store'), [
        'scan_data' => $payload,
    ]);

    $response->assertRedirect(route('blood-inventories.index'));
    expect(BloodInventory::count())->toBe(1);
    $inventory = BloodInventory::first();
    expect($inventory->blood_type_id)->toBe($type->id);
    expect($inventory->donor_id)->toBe($donor->id);
    expect($inventory->blood_bank_id)->toBe($bank->id);
    expect($inventory->quantity)->toBe(3);
});
