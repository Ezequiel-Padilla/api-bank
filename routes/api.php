<?php

use Illuminate\Http\Request;
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

Route::apiResource('cuenta', App\Http\Controllers\AccountController::class);
Route::apiResource('evento', App\Http\Controllers\EventController::class);
Route::post('/reset', function() {
    try {
        Artisan::call('migrate:fresh');
        return 'Reseteado!';
    } catch (\Exception $e) {
        return 'Error al resetear';
    }
});
