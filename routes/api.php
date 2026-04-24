<?php

use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\BloodStorageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(BloodInventoryController::class)->group(function(){
        Route::get('/dashboard','dashboard');
        Route::get('/query', 'query');
    });
    Route::resources([
        'blood-inventories' => BloodInventoryController::class,
        'blood_banks'=>BloodBankController::class,
        'withdrawal'=>WithdrawalController::class,
        'blood-storage'=>BloodStorageController::class,
        'blood-request'=>BloodRequestController::class
    ]);
});
// use auth routes for API
Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
    Route::get('/profile', 'profile')->middleware('auth:sanctum');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
