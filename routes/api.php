<?php

use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\BloodStorageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(BloodInventoryController::class)->group(function(){
        Route::get('/dashboard','dashboard');
        Route::get('/query', 'query');
    });
    Route::controller(HomeController::class)->group(function(){
        Route::get('/report', 'report');
    });
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
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
    Route::get('/profile', 'profile')->middleware('auth:sanctum');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
