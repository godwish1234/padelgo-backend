<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role Middleware
 * Checks if authenticated user has the required role(s)
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Check if user's role is in the allowed roles
        if (!in_array($request->user()->role->value, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Required role(s): ' . implode(', ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
