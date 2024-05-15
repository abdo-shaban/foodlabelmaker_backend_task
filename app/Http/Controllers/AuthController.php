<?php

namespace App\Http\Controllers;

use App\Data\UserLoginDTOData;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserLoginResource;
use App\Models\User;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function login(LoginRequest $request)
    {
        $token = $this->userService->Login(UserLoginDTOData::from($request->validated()));

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return (new UserLoginResource(auth()->user()))
            ->additional([
                'token'      => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
    }

}
