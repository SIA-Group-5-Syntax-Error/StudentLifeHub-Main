<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeApiKeyProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🌟 1. IMMUNITY PASS: If the request is trying to log in, bypass the API Key check completely!
        if ($request->is('api/login') || $request->routeIs('login')) {
            return $next($request);
        }

        // 2. Dynamically read the header name configured in your .env file
        $headerName = env('GATEWAY_API_KEY_HEADER', 'X-API-Key');

        // 3. Capture the incoming key string safely
        $apiKey = $request->header($headerName) ?? $request->bearerToken();

        // 4. Dynamically fetch the expected key value from your .env file
        $expectedKey = env('GATEWAY_API_KEYS');

        // 5. Evaluation check against the environment values
        if (!$apiKey || $apiKey !== $expectedKey) {
            return response()->json([
                'error' => [
                    'code' => 'unauthorized',
                    'message' => 'API key is required. Send X-API-Key or Authorization: Bearer <key>'
                ]
            ], 401);
        }

        return $next($request);
    }
}