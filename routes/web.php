<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Order\IndexOrderController;
use App\Http\Controllers\Order\StoreOrderController;
use App\Http\Controllers\Order\UpdateOrderDeliveryController;
use App\Http\Controllers\Order\UpdateOrderProductsController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->middleware('auth')
    ->name('home');

Route::controller(CustomerController::class)->prefix('customers')->group(function () {
    Route::get('/', 'index')->name('customers');
    Route::get('/create', 'create')->name('customers.create');
    Route::post('/', 'store')->name('customers.store');
    Route::get('/{id}', 'show')->name('customers.show');
    Route::put('/{id}', 'update')->name('customers.update');
    Route::delete('/{id}', 'destroy')->name('customers.destroy');
    Route::get('/{id}/addresses', 'addresses')->name('customers.addresses');
});

Route::controller(AddressController::class)->prefix('addresses')->group(function () {
    Route::post('/', 'store')->name('addresses.store');
    Route::put('/{id}', 'update')->name('addresses.update');
    Route::delete('/{id}', 'destroy')->name('addresses.destroy');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'index')->name('products');
    Route::post('/', 'store')->name('products.store');
    Route::put('/{id}', 'update')->name('products.update');
    Route::delete('/{id}', 'destroy')->name('products.destroy');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'index')->name('categories');
    Route::post('/', 'store')->name('categories.store');
    Route::put('/{id}', 'update')->name('categories.update');
    Route::delete('/{id}', 'destroy')->name('categories.destroy');
});

Route::prefix('orders')->group(function () {
    Route::get('/', [IndexOrderController::class, 'index'])->name('orders');
    Route::get('/create', [StoreOrderController::class, 'create'])->name('orders.create');
    Route::post('/', [StoreOrderController::class, 'store'])->name('orders.store');
    Route::post('/complete/{order}', [IndexOrderController::class, 'complete'])->name('orders.complete');
    Route::get('/{id}', [IndexOrderController::class, 'show'])->name('orders.show');
    Route::get('/{order}/edit-products', [UpdateOrderProductsController::class, 'edit'])->name('orders.edit_products');
    Route::put('/{order}/update/products', [UpdateOrderProductsController::class, 'update'])->name('orders.update_products');
    Route::put('/{order}/update/delivery', [UpdateOrderDeliveryController::class, 'update'])->name('orders.update_delivery');
    Route::delete('/{order}', [IndexOrderController::class, 'destroy'])->name('orders.destroy');
});

require __DIR__.'/auth.php';
