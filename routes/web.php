<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes - API Only
 * This project is API-only with no web frontend
 */

// Redirect root to API documentation
Route::get('/', function () {
    return response()->json([
        'message' => 'PadelGo API v1',
        'version' => '1.0.0',
        'description' => 'RESTful API for PadelGo mobile application',
        'endpoints' => [
            'health' => '/api/health',
            'register' => 'POST /api/v1/auth/register',
            'login' => 'POST /api/v1/auth/login',
            'logout' => 'POST /api/v1/auth/logout (requires token)',
            'profile' => 'GET /api/v1/auth/me (requires token)',
        ],
        'authentication' => 'Bearer Token (use token from register/login in Authorization header)',
    ]);
});
