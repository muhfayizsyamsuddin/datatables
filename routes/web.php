<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung menampilkan Manajemen Produk
Route::get('/', [ProductsController::class, 'index'])
    ->name('products.index');

// URL lama /products diarahkan ke halaman utama
Route::get('/products', function () {
    return redirect()->route('products.index');
})->name('products.redirect');

// Endpoint DataTables server-side
Route::get('/products/data', [ProductsController::class, 'data'])
    ->name('products.data');

// Endpoint form edit
Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])
    ->name('products.edit');

// Route untuk store, update, dan destroy
Route::resource('products', ProductsController::class)
    ->except(['index', 'create', 'show', 'edit']);