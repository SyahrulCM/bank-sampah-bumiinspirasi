<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\TransaksiController;

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

use App\Http\Controllers\Api\AuthNasabahController;

Route::post('/nasabah/login', [AuthNasabahController::class, 'login']);
Route::post('/nasabah/register', [AuthNasabahController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/nasabah/logout', [AuthNasabahController::class, 'logout']);
    Route::get('/nasabah/profile', fn(Request $request) => $request->user());
});

Route::get('/sampah', [SampahController::class, 'apiSampah']);
