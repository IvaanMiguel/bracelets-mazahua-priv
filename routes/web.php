<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)
    ->middleware('auth')
    ->name('home');

Route::controller(CustomerController::class)->prefix('customers')->group(function () {
    Route::get('/', 'index')->name('customers');
    Route::get('/{id}', 'show')->name('customers.show');
    Route::post('/', 'store')->name('customers.store');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'index')->name('products');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'index')->name('categories');
});

Route::controller(OrderController::class)->prefix('orders')->group(function () {
    Route::get('/', 'index')->name('orders');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
