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
    Route::post('location/kid', [App\Http\Controllers\API\LocationController::class, 'getLocationXKid']);

    Route::get('file/kid/{idkid}', [App\Http\Controllers\API\FileController::class, 'getFilesXKid']);
    Route::post('file', [App\Http\Controllers\API\FileController::class, 'store']);
    Route::put('file/{id}', [App\Http\Controllers\API\FileController::class, 'update']);
    Route::get('file/{id}', [App\Http\Controllers\API\FileController::class, 'show']);
    Route::delete('file/{id}', [App\Http\Controllers\API\FileController::class, 'destroy']);

    Route::get('contact/kid/{idkid}', [App\Http\Controllers\API\ContactsController::class, 'getContactsXKid']);
    Route::post('contact', [App\Http\Controllers\API\ContactsController::class, 'store']);
    Route::put('contact/{id}', [App\Http\Controllers\API\ContactsController::class, 'update']);
    Route::get('contact/{id}', [App\Http\Controllers\API\ContactsController::class, 'show']);
    Route::delete('contact/{id}', [App\Http\Controllers\API\ContactsController::class, 'destroy']);

    Route::get('contact/call/{idcontact}', [App\Http\Controllers\API\CallController::class, 'getCallsXContact']);
    Route::post('contact/call', [App\Http\Controllers\API\CallController::class, 'store']);
    Route::put('contact/call/{id}', [App\Http\Controllers\API\CallController::class, 'update']);
    Route::get('contact/call/{id}', [App\Http\Controllers\API\CallController::class, 'show']);
    Route::delete('contact/call/{id}', [App\Http\Controllers\API\CallController::class, 'destroy']);

    Route::get('content/index', [App\Http\Controllers\API\ContentController::class, 'index']);
    Route::get('content/quantity', [App\Http\Controllers\API\ContentController::class, 'quantity_of_content']);
    Route::get('content/{idKid}', [App\Http\Controllers\API\ContentController::class, 'contentXKid']);
    Route::get('content/children', [App\Http\Controllers\API\ContentController::class, 'contentXChildren']);
    Route::post('content/store', [App\Http\Controllers\API\ContentController::class, 'store']);
    Route::put('content/{id}', [App\Http\Controllers\API\ContentController::class, 'update']);
    Route::get('content/{id}', [App\Http\Controllers\API\ContentController::class, 'show']);
    Route::delete('content/{id}', [App\Http\Controllers\API\CallController::class, 'destroy']);
});
