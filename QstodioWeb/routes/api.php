<?php

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

Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);


Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::post('profile', [App\Http\Controllers\API\AuthController::class, 'profile']);
    Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    
});
