<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Services\API\BookController;
use App\Http\Controllers\Services\API\AnnouncementController;
use App\Http\Controllers\Services\API\DictionaryController;
use App\Http\Controllers\Services\API\TaskController;
use App\Http\Controllers\Services\API\AuthController;
use App\Http\Middleware\ChangeApiKeyProtection;

// 🌟 Public Login Endpoint
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([ChangeApiKeyProtection::class])->group(function () {

    // 📦 MODULE A: Book Service (Local Search Engine Subsystem)
    Route::get('/books/search', [BookController::class, 'search']);

    // 📦 MODULE B: Dictionary Service (External API Proxy Subsystem)
    Route::get('/dictionary/search', [DictionaryController::class, 'search']);

    // 📦 MODULE C: Announcement Service (Proxying via Remote Gateway Subsystem)
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index']); 
        Route::get('/{id}', [AnnouncementController::class, 'show']);
        Route::post('/', [AnnouncementController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [AnnouncementController::class, 'update']);
        Route::delete('/{id}', [AnnouncementController::class, 'destroy']);
    });

    // 📦 MODULE D: Todo/Task Service (MockAPI Proxy Subsystem)
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);
    });

    // 📦 MODULE E: Class Schedule Service (MockAPI Web Service Proxy & Bulletproof Filter)
    Route::get('/schedule', function () {
        $response = Http::get('https://6a07932ffa9b27c848fa2d18.mockapi.io/Schedule');
        return response()->json($response->json());
    });

    Route::get('/schedule/{day}', function ($day) {
        $response = Http::get('https://6a07932ffa9b27c848fa2d18.mockapi.io/Schedule');

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reach upstream external mock data system layer provider.'
            ], 502);
        }

        $rawData = $response->json();
        $schedules = collect($rawData);

        $filtered = $schedules->filter(function ($item) use ($day) {
            $dbDay = $item['Day'] ?? $item['day'] ?? null;

            if (is_null($dbDay)) {
                return false;
            }

            return trim(strtolower($dbDay)) === trim(strtolower($day));
        });

        if ($filtered->isEmpty() && !empty($rawData)) {
            return response()->json([
                'error' => "No match found for your query: '{$day}'",
                'solution' => "Check the spelling below. You must type it exactly as it appears in the raw data.",
                'raw_data_sample_from_mockapi' => array_slice($rawData, 0, 3)
            ], 200, [], JSON_PRETTY_PRINT);
        }

        return response()->json($filtered->values());
    });

}); 