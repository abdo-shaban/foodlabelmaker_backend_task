<?php

namespace App\Services;

use App\Data\UserLoginDTOData;

class UserService
{
    public function Login(UserLoginDTOData $userLoginDTOData)
    {
        $credentials = ['email' => $userLoginDTOData->email, 'password' => $userLoginDTOData->password];

        return auth()->attempt($credentials);
    }
}
