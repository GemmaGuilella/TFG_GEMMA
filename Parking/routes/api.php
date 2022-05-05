<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarrierController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\GenerateQR;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShowLogs;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/user', [AuthController::class, 'user'])->name('user');
    Route::put('/user', [AuthController::class, 'saveUser'])->name('user.edit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/cars/{car}/qr', GenerateQR::class);
Route::apiResource('cars', CarController::class);
Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
Route::get('logs', ShowLogs::class)->name('logs.index');
Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('barriers/open', [BarrierController::class, 'open'])->name('barriers.open');

Route::as('checkout.')->prefix('/checkout')->group(function () {
    Route::post('/{car}', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/error', [PaymentController::class, 'error'])->name('error');
});
