<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\AppException;

class AuthService
{
    /**
     * Login the user
     * 
     * @param array $credentials
     * @return User
     * @throws \Exception
     */
    public function login(array $credentials)
    {
        if (!auth()->attempt($credentials)) {
            throw new AppException('Invalid credentials', 422);
        }

        $user = auth()->user();
        $user->token = $user->createToken('token')->plainTextToken;

        return $user;
    }
}