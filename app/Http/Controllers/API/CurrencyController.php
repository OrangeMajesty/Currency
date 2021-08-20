<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\CurrencyType;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CurrencyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currencies = Currency::all();
        $query = $currencies->sortByDesc('created_at')->unique('currency_id');
        $query = $query->toQuery()->paginate();
        return $this->sendResponse(
            CurrencyResource::collection($query),
            "Successful",
            Response::HTTP_OK
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $code
     * @param string|null $date format dd.mm.YYYY
     * @return JsonResponse
     */
    public function show(string $code, string $date = null): JsonResponse
    {
        try {
            $query = Currency::query();
            $type = CurrencyType::query()->where('code', '=', $code)->first(['id', 'code']);

            $param = [
                ['currency_id', '=', $type->id]
            ];

            if(isset($date))
            {
                $param = array_merge($param, [
                    ['created_at', '=', new DateTime($date)]
                ]);
            }

            $query = $query->where($param)->get()->last();
            if(!$query)
                throw new \Exception("Currency rate is empty");

            return $this->sendResponse(
                new CurrencyResource($query),
                "Successful",
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return $this->sendResponse(
                null,
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
