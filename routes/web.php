<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Dashboard
Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');

// Products - CRUD Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::get('/products/{id}/delete', [ProductController::class, 'showDelete'])->name('products.show-delete');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// Default route
Route::get('/', function () {
    return redirect()->route('dashboard');
});