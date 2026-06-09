<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\BloodStorageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RelayStatusController;
use App\Http\Controllers\UserBankController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::controller(RelayStatusController::class)->group(function () {
    Route::get('/get-pending-command', 'actuate');
    Route::get('/update-relay/{id}', 'actuate');
    Route::post('/relay-status/store','store')->middleware('auth:sanctum');
});
Route::middleware(['auth:sanctum', 'verified', 'isVerified'])->group(function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/banks', 'banks');
    });
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/dashboard', 'index');
    });
    Route::controller(UserBankController::class)->group(function () {
        Route::get('/bank_users', 'index');
    });
    Route::controller(BloodInventoryController::class)->group(function(){
        Route::get('/dashboard/{bank_id}','dashboard');
        Route::get('/query', 'query');
    });
    
    Route::controller(HomeController::class)->group(function(){
        Route::get('/report', 'report');
    })->middleware('isAdmin');
    Route::apiResources([
        'blood-inventories' => BloodInventoryController::class,
        'blood_banks' => BloodBankController::class,
        'blood-storage' => BloodStorageController::class,
        'blood-request' => BloodRequestController::class
    ]);
    Route::controller(WithdrawalController::class)->prefix('blood-withdrawals/')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/{id}/edit', 'edit');
        Route::put('/{id}/update', 'update');
        Route::delete('/{id}/delete', 'destroy');
        Route::get('/{id}/show', 'show');
    });
});

// use auth routes for API
Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'register')->middleware(['auth:sanctum','throttle:60,1']);
    Route::post('/login', 'login')->middleware('throttle:5,1');
    Route::post('/forgot-password', 'forgotPassword')->middleware('throttle:5,1');
    Route::post('/reset-password', 'resetPassword')->middleware('throttle:5,1');
    Route::get('/profile', 'profile')->middleware(['auth:sanctum','throttle:60,1']);
    Route::post('/logout', 'logout')->middleware(['auth:sanctum','throttle:60,1']);
    Route::post('/confirmUser', 'confirmUser')->middleware(['auth:sanctum','throttle:60,1']);
});
Route::get('/sendSms/{phone}/{message}', [BloodInventoryController::class, 'sendSMS'])->name('send.sms');