<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;

/**
 * Auth Controller
 * Handles user authentication
 */
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $token = $this->authService->createToken($user);

        return $this->success(
            [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
                'token' => $token,
            ],
            'User registered successfully',
            201
        );
    }

    /**
     * Login a user
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login(
            $request->email,
            $request->password
        );

        if (!$user) {
            throw new UnauthorizedException('Invalid email or password');
        }

        $token = $this->authService->createToken($user);

        return $this->success(
            [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
                'token' => $token,
            ],
            'Login successful'
        );
    }

    /**
     * Get current authenticated user
     */
    public function me()
    {
        $user = auth()->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);
    }

    /**
     * Logout a user (revoke token)
     */
    public function logout()
    {
        $this->authService->revokeToken(auth()->user());

        return $this->success(
            [],
            'Logout successful'
        );
    }
}
