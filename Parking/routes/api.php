<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarrierController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DecoderQR;
use App\Http\Controllers\GenerateQR;
use App\Http\Controllers\SpaceController;
use App\Models\Barrier;
use Illuminate\Http\Request;
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
Route::get('/car/{car}/qr', GenerateQR::class);
Route::apiResource('cars', CarController::class);
Route::apiResource('barriers', BarrierController::class)->only('update');
Route::post('barriers/open', [BarrierController::class, 'open'])->name('barriers.open');
// Route::post('/car/{qr}', DecoderQR::class);
// Route::put('/spaces/{space}/associate/{car}', [SpaceController::class, 'associate'])->name('space.associate');
// Route::put('/spaces/{space}/dissociate', [SpaceController::class, 'disassociate'])->name('space.disassociate');
