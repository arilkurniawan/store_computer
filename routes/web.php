<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ========================================
// PUBLIC ROUTES (Guest bisa akses)
// ========================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Browse Products
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{product:slug}', 'show')->name('products.show');
    Route::get('/category/{category:slug}', 'byCategory')->name('products.category');
});

Route::get('/api/products/search', [ProductController::class, 'apiSearch'])->name('api.products.search');

// ========================================
// AUTH ROUTES (Login, Register, etc.)
// ========================================

require __DIR__.'/auth.php';

// ========================================
// AUTHENTICATED ROUTES (Harus login)
// ========================================

Route::middleware('auth')->group(function () {

    // ----------------------------------------
    // DASHBOARD
    // ----------------------------------------
    Route::get('/dashboard', function () {
        return redirect()->route('orders.index');
    })->name('dashboard');

    // ----------------------------------------
    // PROFILE ROUTES
    // ----------------------------------------
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });

    // ----------------------------------------
    // CART ROUTES
    // ----------------------------------------
    Route::controller(CartController::class)
        ->prefix('cart')
        ->name('cart.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::patch('/{cart}', 'update')->name('update');
            Route::delete('/{cart}', 'destroy')->name('destroy');
            Route::delete('/', 'clear')->name('clear');
            Route::get('/count', 'count')->name('count');
        });

    // ----------------------------------------
    // CHECKOUT ROUTES
    // ----------------------------------------
    Route::controller(CheckoutController::class)
        ->prefix('checkout')
        ->name('checkout.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

Route::post('/promo/validate', [\App\Http\Controllers\PromoController::class, 'validate'])
        ->name('promo.validate');

    // ----------------------------------------
    // PAYMENT ROUTES
    // ----------------------------------------
    Route::controller(PaymentController::class)
        ->prefix('payment')
        ->name('payment.')
        ->group(function () {
            Route::get('/{order}', 'confirm')->name('confirm');
            Route::post('/{order}/upload', 'uploadProof')->name('upload');
        });

    // ----------------------------------------
    // ORDER ROUTES
    // ----------------------------------------
    Route::controller(OrderController::class)
        ->prefix('orders')
        ->name('orders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
            Route::post('/{order}/cancel', 'cancel')->name('cancel');
            Route::post('/{order}/confirm-received', 'confirmReceived')->name('confirm-received');
        });

}); // ← Tutup middleware auth group
