<?php

use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController; // এটি নিশ্চিত করুন

// 1. home redirect from dashboard
Route::get('/dashboard', function () {
    return redirect()->route('home');
});

// 2. home and general product list
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index'); 

// 3. profile routes
Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 4. admin product management routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    //  AdminProductController 
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}/update', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

// 5. product detail page
// public product detail route
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// 6. authentication routes (this should be at the end)
require __DIR__.'/auth.php';