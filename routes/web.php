<?php

use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\BloodStorageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', function () {
        return redirect('/dashboard');
    })->name('home');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        // Blood Inventory
        Route::resources([
            'inventories' => BloodInventoryController::class,
            'requests' => BloodRequestController::class,
            'storages' => BloodStorageController::class,
            'banks' => BloodBankController::class,
            'withdrawals' => WithdrawalController::class
        ]);
    });
    // Profile routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile.index');
        Route::put('/profile/update', 'updateProfile')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
        Route::put('/profile/notifications', 'updateNotifications')->name('profile.notifications');
        Route::get('/profile/edit/{id}', 'edit')->name('profile.edit');
    });
});
require __DIR__ . '/auth.php';
