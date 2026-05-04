<?php

use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController; // এটি নিশ্চিত করুন

// ১. ড্যাশবোর্ড থেকে হোমে রিডাইরেক্ট
Route::get('/dashboard', function () {
    return redirect()->route('home');
});

// ২. হোম এবং সাধারণ প্রোডাক্ট লিস্ট
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index'); 

// ৩. প্রোফাইল রাউটস
Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* 
  ❌ এখান থেকে মাঝখানের কাস্টম Route::post('/login', ...) ডিলিট করে দিয়েছি। 
  এটিই আপনাকে হোমে পাঠিয়ে দিচ্ছিল।
*/

// ৪. অ্যাডমিন রাউটস (Middleware এবং Correct Controller নিশ্চিত করা হয়েছে)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // এখানে AdminProductController ব্যবহার করা হয়েছে
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}/update', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

// ৫. ব্রিজের অথেনটিকেশন রাউটস (এটি শেষে থাকা জরুরি)
require __DIR__.'/auth.php';