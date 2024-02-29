<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // You can access the authenticated user using $user
            $request->merge(['user' => $user]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
