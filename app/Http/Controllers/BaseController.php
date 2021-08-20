<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($data, string $message, int $code) : JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
