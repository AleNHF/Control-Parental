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
    
    Route::post('profile/update', [App\Http\Controllers\API\TutorController::class, 'update']);
    Route::get('tutor/getChildren', [App\Http\Controllers\API\TutorController::class, 'getChildren']);

    Route::post('children/store', [App\Http\Controllers\API\ChildrenController::class, 'store']);

    Route::post('location/store', [App\Http\Controllers\API\LocationController::class, 'store']);
    Route::get('location/{kid-id}', [App\Http\Controllers\API\LocationController::class, 'index']);
});
