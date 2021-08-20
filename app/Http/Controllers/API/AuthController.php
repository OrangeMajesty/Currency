<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends BaseController
{
    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken("passport")->access_token;
        return $this->sendResponse(
            ['Bearer' => $token],
            "Successful",
            Response::HTTP_OK
        );
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {

        if(auth()->attempt($request->validated()))
        {
            $token = auth()->user()->createToken("passport")->accessToken;
            return $this->sendResponse(
                ['Bearer' => $token],
                "Successful",
                Response::HTTP_OK
            );
        }

        return $this->sendResponse(
            null,
            "Unauthorised Access",
            Response::HTTP_UNAUTHORIZED
        );
    }
}
