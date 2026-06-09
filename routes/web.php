<?php

use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\BloodStorageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        if (Auth::check()) {
            if (Auth::user()->role == 'Superadmin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/dashboard');
        }
        return view('welcome');
    });

    Route::get('/home', function () {
        return redirect('/dashboard');
    })->name('home');
    // Route::get('sendSms/{phone}/{message}', [BloodInventoryController::class, 'sendSMS'])->name('send.sms');
    Route::middleware(['auth','verified', 'isVerified'])->group(function () {

        // Admin routes
        Route::middleware('isSuperadmin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::get('/admin/banks/search', [AdminController::class, 'searchBanks'])->name('admin.banks.search');
            Route::post('/admin/login-as/{bankId}', [AdminController::class, 'loginAs'])->name('admin.login_as');
        });
        Route::get('/reports', [HomeController::class, 'reportsPage'])->name('reports');
        Route::get('/emailreport', [HomeController::class, 'exportReport'])->name('exportReport');

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/blood-dashboard', [BloodInventoryController::class, 'dashboard'])->name('dashboard');
        // Blood Inventory
        Route::resources([
            'inventories' => BloodInventoryController::class,
            'requests' => BloodRequestController::class,
            'storages' => BloodStorageController::class,
            'banks' => BloodBankController::class,
            'withdrawals' => WithdrawalController::class,
            'system-users' => UserController::class,
        ]);
        // Profile routes
        Route::controller(UserController::class)->group(function () {
            Route::get('/profile', 'profile')->name('profile.index');
            Route::put('/profile/update', 'updateProfile')->name('profile.update');
            Route::put('/profile/password', 'updatePassword')->name('profile.password');
            Route::put('/profile/notifications', 'updateNotifications')->name('profile.notifications');
            Route::get('/profile/edit/{id}', 'edit')->name('profile.edit');
        });
    });
    
});
require __DIR__ . '/auth.php';