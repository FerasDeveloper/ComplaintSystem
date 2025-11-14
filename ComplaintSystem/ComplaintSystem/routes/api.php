<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);


Route::middleware(['auth:sanctum'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::post('/government/employee', [AuthController::class, 'createEmployee']); 
  Route::post('/addgovernment', [AuthController::class, 'createGovernment']); 
});




// test cach
Route::get('/cache-test', function () {
    Cache::put("test_key", "hello from redis", 60);
    return Cache::get("test_key");
});

