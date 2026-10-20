<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

// ── Products ────────────────────────────────────────────────────────────────

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

// ── Categories ──────────────────────────────────────────────────────────────

// Halaman manajemen kategori
Route::get('/categories', [CategoriesController::class, 'index'])
    ->name('categories.index');

// Endpoint DataTables server-side
Route::get('/categories/data', [CategoriesController::class, 'data'])
    ->name('categories.data');

// Endpoint form edit (JSON)
Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])
    ->name('categories.edit');

// Route untuk store, update, dan destroy
Route::resource('categories', CategoriesController::class)
    ->except(['index', 'create', 'show', 'edit']);