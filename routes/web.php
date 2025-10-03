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

Route::get('/h', function () {
    return view('front.arkadaslaraBol.index');
})->name('home');

// Ana sayfa (auth zorunlu)
Route::middleware('auth')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('home');
    Route::get('/para-gonder', [FrontController::class, 'paraGonder'])->name('para-gonder');
    Route::get('/odeme-yap', [FrontController::class, 'odemeYap'])->name('odeme-yap');
    Route::get('/arkadas-bol', [FrontController::class, 'arkadasBol'])->name('arkadas-bol');
    Route::post('/load-money', [FrontController::class, 'loadMoney'])->name('load-money');
    Route::post('/send-money', [FrontController::class, 'sendMoney'])->name('send-money');
    Route::post('/process-qr-payment', [FrontController::class, 'processQrPayment'])->name('process-qr-payment');
    Route::post('/create-split', [FrontController::class, 'createSplit'])->name('create-split');
    Route::post('/accept-split', [FrontController::class, 'acceptSplit'])->name('accept-split');
    Route::post('/reject-split', [FrontController::class, 'rejectSplit'])->name('reject-split');
    Route::get('/cashback-info', [FrontController::class, 'getCashbackInfo'])->name('cashback-info');
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



