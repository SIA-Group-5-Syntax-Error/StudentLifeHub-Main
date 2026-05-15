<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('MOCKAPI_URL');
    }

    // GET TASKS
    public function index(): JsonResponse
    {
        $response = Http::get($this->apiUrl);

        return response()->json(
            $response->json()
        );
    }

    // ADD TASK
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required'
        ]);

        $response = Http::post($this->apiUrl, [
            'title' => $request->title,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Task added successfully',
            'data' => $response->json()
        ]);
    }

    // UPDATE TASK
    public function update(Request $request, string $id): JsonResponse
    {
        $response = Http::put($this->apiUrl . '/' . $id, [
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $response->json()
        ]);
    }

    // DELETE TASK
    public function destroy(string $id): JsonResponse
    {
        Http::delete($this->apiUrl . '/' . $id);

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}