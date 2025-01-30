<?php

namespace App\Http\Controllers;

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

        return response()->json($user, 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $token = $this->userService->login($validated);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        $this->userService->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function protectedRoute()
    {
        return response()->json(['message' => 'You are authenticated']);
    }
}
