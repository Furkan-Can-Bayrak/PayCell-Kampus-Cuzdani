<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ana sayfa (auth zorunlu)
Route::middleware('auth')->group(function () {
    Route::get('/', [FrontController::class, 'index']);
    Route::get('/para-gonder', [FrontController::class, 'paraGonder'])->name('para-gonder');
    Route::post('/load-money', [FrontController::class, 'loadMoney'])->name('load-money');
});

// Auth routes
Route::prefix('auth')->group(function () {
    // Sadece misafirler erişebilsin
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
    });

    // Giriş yapmış kullanıcılar
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
