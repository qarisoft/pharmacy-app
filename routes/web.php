<?php

use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Sales\SaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
//    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);

});




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
