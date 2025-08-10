<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(private AuthService $authService)
    {
        //...
    }

    /**
     * Login the user
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->getResponse($this->authService->login($request->validated()));
    }
}