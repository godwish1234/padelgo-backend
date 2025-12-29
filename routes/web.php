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
        'documentation' => 'See /api/v1 routes',
        'health' => '/api/health',
    ]);
});
