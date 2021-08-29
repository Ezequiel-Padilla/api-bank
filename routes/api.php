<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
Route::apiResource('accounts', App\Http\Controllers\API\AccountController::class);
Route::apiResource('events', App\Http\Controllers\API\EventController::class);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('events', [App\Http\Controllers\API\EventController::class, 'store']);
});
Route::post('/reset', function() {
    return Artisan::call('migrate:fresh');    
});
