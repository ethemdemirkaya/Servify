<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiningTableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pos/get-order/{tableId}', [App\Http\Controllers\PosController::class, 'getTableOrder'])->name('pos.get-order');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [PosController::class, 'store'])->name('pos.store');
    Route::resource('users', UserController::class);
    Route::get('/orders/active', [OrderController::class, 'active'])->name('orders.active');

    Route::resource('tables', DiningTableController::class);
    Route::resource('reservations', ReservationController::class);
    Route::get('/reservations/check-availability/{tableId}', [ReservationController::class, 'getAvailability'])->name('reservations.check');

    Route::resource('products', ProductController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('variations', \App\Http\Controllers\ProductVariationController::class);

    Route::resource('ingredients', \App\Http\Controllers\IngredientController::class);

    Route::resource('product-recipes', \App\Http\Controllers\ProductRecipeController::class);

    Route::resource('inventory/transactions', \App\Http\Controllers\InventoryTransactionController::class)
        ->names([
            'index' => 'inventory.transactions.index',
            'store' => 'inventory.transactions.store',
            'destroy' => 'inventory.transactions.destroy',
            // update ve edit metodlarını kullanmadık ama resource hepsini oluşturur.
        ]);
    Route::get('/orders/kitchen', [App\Http\Controllers\KitchenController::class, 'index'])->name('orders.kitchen');
    Route::post('/orders/kitchen/{id}/status', [App\Http\Controllers\KitchenController::class, 'updateStatus'])->name('orders.kitchen.update');
    Route::post('/orders/kitchen/item/{id}', [App\Http\Controllers\KitchenController::class, 'updateItemStatus'])->name('orders.kitchen.item.update');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

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
