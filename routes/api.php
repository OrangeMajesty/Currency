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
Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function() {

    Route::get('/currencies', function() {
        $query = \App\Models\Currency::all();
        $query = $query->sortByDesc('created_at')->unique('currency_id');
        return \App\Http\Resources\CurrencyResource::collection($query->toQuery()->paginate());
    });

    // date format dd.mm.YYYY
    Route::get('/currency/{code}/{date?}', function(string $code, string $date = null) {
        try {
            $query = \App\Models\Currency::query();
            $type = \App\Models\CurrencyType::query()->where('code', '=', $code)->first(['id', 'code']);

            $param = [
                ['currency_id', '=', $type->id]
            ];

            if($date)
                $param = array_merge($param, [
                    ['created_at', '=', new DateTime($date)]
                ]);


            $query = $query->where($param)->get()->first();
            if(!$query)
                throw new \Exception("Currency rate is empty");

            return new \App\Http\Resources\CurrencyResource($query);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'error' => true
            ], 500);
        }

    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'API resource not found',
        'error' => true
    ], 404);
});
