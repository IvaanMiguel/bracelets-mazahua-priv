<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->middleware('auth')
    ->name('home');

Route::controller(CustomerController::class)->prefix('customers')->group(function () {
    Route::get('/', 'index')->name('customers');
    Route::get('/{id}', 'show')->name('customers.show');
    Route::post('/', 'store')->name('customers.store');
    Route::put('/{id}', 'update')->name('customers.update');
});

Route::controller(AddressController::class)->prefix('addresses')->group(function () {
    Route::post('/', 'store')->name('addresses.store');
    Route::delete('/{id}', 'destroy')->name('addresses.destroy');
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

require __DIR__.'/auth.php';
