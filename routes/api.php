<?php

use Illuminate\Http\Request;
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

Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login']);
//Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/currencies', [\App\Http\Controllers\API\CurrencyController::class, 'index']);
    Route::get('/currency/{code}/{date?}', [\App\Http\Controllers\API\CurrencyController::class, 'show']);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found',
    ], 404);
});
