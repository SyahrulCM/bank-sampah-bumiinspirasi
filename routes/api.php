<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Api\TransaksiNasabahController;
use App\Http\Controllers\Api\AuthNasabahController;
use App\Http\Controllers\Api\EdukasiApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Login & Register (tanpa token)
Route::post('/nasabah/login', [AuthNasabahController::class, 'login']);
Route::post('/nasabah/register', [AuthNasabahController::class, 'register']);

// Proteksi pakai token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/nasabah/logout', [AuthNasabahController::class, 'logout']);
    Route::get('/nasabah/profile', fn(Request $request) => $request->user());
    Route::get('/nasabah/transaksi', [TransaksiNasabahController::class, 'getTransaksiNasabah']);
    
});

// Public (data sampah)
Route::get('/sampah', [SampahController::class, 'apiSampah']);
Route::get('/edukasi', [EdukasiApiController::class, 'apiEdukasi']);

