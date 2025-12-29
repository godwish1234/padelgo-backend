<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Auth Service
 * Handles authentication business logic
 */
class AuthService
{
    /**
     * Register a new user
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => UserRole::USER,
        ]);
    }

    /**
     * Attempt to log in a user
     */
    public function login(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Create an API token for a user
     */
    public function createToken(User $user, string $deviceName = 'mobile'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * Revoke a user's token
     */
    public function revokeToken(User $user): bool
    {
        return (bool) $user->currentAccessToken()->delete();
    }

    /**
     * Revoke all tokens for a user
     */
    public function revokeAllTokens(User $user): int
    {
        return $user->tokens()->delete();
    }
}
