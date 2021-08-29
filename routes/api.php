<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('accounts', App\Http\Controllers\API\AccountController::class);
    Route::apiResource('events', App\Http\Controllers\API\EventController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::post('/reset', function() {
    return Artisan::call('migrate:fresh');    
});
