<?php

namespace App\Services\Gateway;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class RemoteGateway
{
    /**
     * Proxies the request to the upstream microservice.
     */
    public function resource(string $service, string $method, string $id = '', array $data = []): Response
    {
        // Replace this with the actual URL of your announcement microservice
        $baseUrl = env('ANNOUNCEMENT_SERVICE_URL', 'http://localhost:8001/api');
        $url = rtrim($baseUrl, '/') . '/' . $service . ($id ? '/' . $id : '');

        $response = Http::send($method, $url, [
            'json' => $data
        ]);

        return response($response->body(), $response->status())
                ->header('Content-Type', 'application/json');
    }
}