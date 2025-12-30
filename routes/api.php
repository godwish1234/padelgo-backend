<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourtController;
use App\Http\Controllers\Api\V1\MatchController;
use App\Http\Controllers\Api\V1\ScoringController;

/**
 * API v1 Routes
 * All routes are prefixed with /api/v1
 */

Route::prefix('v1')->group(function () {
    /**
     * Public Auth Routes
     */
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    /**
     * Protected Routes (require authentication)
     */
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Court routes
        Route::prefix('courts')->group(function () {
            Route::get('/', [CourtController::class, 'index']);
            Route::post('/', [CourtController::class, 'store'])->middleware('role:court_admin,super_admin');
            Route::get('/nearby', [CourtController::class, 'nearby']);
            Route::get('/{court}', [CourtController::class, 'show']);
            Route::put('/{court}', [CourtController::class, 'update']);
            Route::delete('/{court}', [CourtController::class, 'destroy']);
        });

        // Match routes
        Route::prefix('matches')->group(function () {
            Route::get('/', [MatchController::class, 'index']);
            Route::post('/', [MatchController::class, 'store']);
            Route::get('/nearby', [MatchController::class, 'nearby']);
            Route::get('/{match}', [MatchController::class, 'show']);
            Route::put('/{match}', [MatchController::class, 'update']);
            Route::delete('/{match}', [MatchController::class, 'destroy']);
            Route::post('/{match}/join', [MatchController::class, 'join']);
            Route::post('/{match}/leave', [MatchController::class, 'leave']);
            Route::get('/{match}/players', [MatchController::class, 'players']);
        });

        // Scoring routes
        Route::prefix('matches/{match}/scoring')->group(function () {
            Route::get('/', [ScoringController::class, 'show']);
            Route::post('/sets', [ScoringController::class, 'createSet']);
            Route::post('/sets/{set}/games', [ScoringController::class, 'createGame']);
            Route::put('/sets/{set}/games/{game}', [ScoringController::class, 'updateGameScore']);
            Route::post('/sets/{set}/complete', [ScoringController::class, 'completeSet']);
            Route::post('/finish', [ScoringController::class, 'finishMatch']);
        });
    });
});

/**
 * Health check endpoint (no auth required)
 */
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});
