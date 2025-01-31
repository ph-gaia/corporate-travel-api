<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(AuthRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userService->create($validated);

        return ApiResponse::created('User created successfully', $user);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $token = $this->userService->login($validated);

        if (!$token) {
            return ApiResponse::unauthorized();
        }

        return ApiResponse::success("Login successful", ['token' => $token]);
    }

    public function logout()
    {
        $this->userService->logout();
        return ApiResponse::success('Logged out');
    }

    public function protectedRoute()
    {
        return ApiResponse::success('You are authenticated');
    }
}
