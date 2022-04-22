<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::post('login', [\App\Http\Controllers\LoginController::class, 'index']);
Route::post('register', [\App\Http\Controllers\RegisterController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('purchase', [\App\Http\Controllers\SubscriptionController::class, 'index']);
    Route::post('check_subscription', [\App\Http\Controllers\SubscriptionController::class, 'check_subscription']);
});



