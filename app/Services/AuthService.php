<?php

namespace App\Services;

use App\Models\User;

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
            throw new \Exception('Invalid credentials');
        }

        $user = auth()->user();
        $user->token = $user->createToken('token')->plainTextToken;

        return $user;
    }
}