<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
/* AŞAĞIDAKİ MİDDLEWARELER ÖRNEKTİR
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'index']);
});

// YÖNETİCİ ve GARSON erişebilir (Sipariş işlemleri)
Route::middleware(['auth', 'role:admin,waiter'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/pos', [PosController::class, 'index']);
});

// Sadece ŞEF (Mutfak) erişebilir
Route::middleware(['auth', 'role:chef,admin'])->group(function () {
    Route::get('/kitchen', [KitchenController::class, 'index']);
});
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
