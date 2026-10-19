<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Product CRUD & DataTables
Route::get('/products/data', [ProductsController::class, 'data'])->name('products.data');
Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
Route::resource('products', ProductsController::class)->except(['create', 'show', 'edit']);
