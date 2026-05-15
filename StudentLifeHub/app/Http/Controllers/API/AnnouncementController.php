<?php

namespace App\Http\Controllers;

use App\Services\Gateway\RemoteGateway;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementController extends Controller
{
    public function __construct(
        private RemoteGateway $gateway,
    ) {}

    /**
     * Display a listing of announcements (Formatted for API).
     */
    public function index(): JsonResponse
    {
        $upstream = $this->gateway->resource('announcements', 'GET', '');

        if ($upstream->getStatusCode() >= 400) {
            return response()->json(['error' => 'Could not load announcements.'], $upstream->getStatusCode());
        }

        $data = json_decode($upstream->getContent(), true);
        
        // Ensure data is an array
        $rows = is_array($data) ? (array_is_list($data) ? $data : [$data]) : [];

        // Format the data consistently
        $announcements = array_map(static function (array $row): array {
            $ts = $row['date'] ?? null;
            return [
                'title'   => $row['title'] ?? 'No Title',
                'date'    => is_numeric($ts) ? date('Y-m-d H:i', (int) $ts) : (string) $ts,
                'content' => $row['content'] ?? $row['description'] ?? '',
            ];
        }, $rows);

        return response()->json([
            'status' => 'success',
            'data'   => $announcements
        ]);
    }

    public function show(string $id): Response
    {
        return $this->gateway->resource('announcements', 'GET', $id);
    }

    public function store(Request $request): Response
    {
        $request->validate(['title' => 'required']);

        return $this->gateway->resource('announcements', 'POST', '', [
            'title' => $request->title,
            'content' => $request->content // added content field
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $verb = $request->isMethod('patch') ? 'PATCH' : 'PUT';

        return $this->gateway->resource('announcements', $verb, $id, [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);
    }

    public function destroy(string $id): Response
    {
        return $this->gateway->resource('announcements', 'DELETE', $id);
    }
}