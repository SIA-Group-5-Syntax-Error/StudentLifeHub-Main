<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeApiKeyProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        $headerName = env('GATEWAY_API_KEY_HEADER', 'X-API-Key');

        $apiKey = $request->header($headerName) ?? $request->bearerToken();

        $expectedKey = env('GATEWAY_API_KEYS');
     
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