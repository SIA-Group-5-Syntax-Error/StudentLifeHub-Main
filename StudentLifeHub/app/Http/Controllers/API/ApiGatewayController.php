<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class RemoteGateway
{
    /**
     * Proxies the request to the upstream microservice.
     */
    public function resource(string $service, string $method, string $id = '', array $data = []): Response
    {
        $baseUrl = env('ANNOUNCEMENT_SERVICE_URL', 'http://localhost:8001/api');
        $url = rtrim($baseUrl, '/') . '/' . $service . ($id ? '/' . $id : '');

        try {
            $response = Http::send($method, $url, [
                'json' => $data
            ]);

            return response($response->body(), $response->status())
                ->header('Content-Type', 'application/json');
                
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'The announcement microservice on port 8001 is offline or unreachable.',
                'debug' => $e->getMessage()
            ], 502);
        }
    }
}