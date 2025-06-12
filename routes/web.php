<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductPriceController;
use Illuminate\Support\Facades\Route;

// Route publik
Route::get('/', function () {
    return view('welcome');
});

// Group route yang hanya bisa diakses user login
Route::middleware(['auth'])->group(function () {


// Cashier
 Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');        // Tampilkan daftar transaksi
    Route::post('/cashier', [CashierController::class, 'store'])->name('cashier.store');        // Simpan transaksi baru
    Route::put('/cashier/{cashier}', [CashierController::class, 'update'])->name('cashier.update');  // Update transaksi (dari modal)
    Route::delete('/cashier/{cashier}', [CashierController::class, 'destroy'])->name('cashier.destroy'); // Hapus transaksi


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Production (form & tabel)

    // CRUD untuk production
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
    Route::post('/productions', [ProductionController::class, 'store'])->name('productions.store');
    Route::put('/productions/{production}', [ProductionController::class, 'update'])->name('productions.update');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('productions.destroy');
    Route::put('/product-prices/{id}', [ProductPriceController::class, 'update'])->name('product-prices.update');


    // CRUD untuk product price
    Route::get('/product-prices', [ProductPriceController::class, 'index'])->name('product_prices.index');
    Route::post('/product-prices', [ProductPriceController::class, 'store'])->name('product_prices.store');
    Route::put('/product-prices/{productPrice}', [ProductPriceController::class, 'update'])->name('product_prices.update');
    Route::delete('/product-prices/{productPrice}', [ProductPriceController::class, 'destroy'])->name('product_prices.destroy');
    Route::get('/product-prices/{productPrice}/edit', [ProductPriceController::class, 'edit'])->name('product_prices.edit');


});

require __DIR__.'/auth.php';
