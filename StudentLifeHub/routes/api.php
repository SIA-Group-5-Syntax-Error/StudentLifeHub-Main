<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\DictionaryController;
use App\Http\Controllers\API\TaskController;
use App\Http\Middleware\ChangeApiKeyProtection;

Route::middleware([ChangeApiKeyProtection::class])->group(function () {

    Route::get('/books/search', [BookController::class, 'search']);

    Route::get('/dictionary/search', [DictionaryController::class, 'search']);

    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index']); 
        Route::get('/{id}', [AnnouncementController::class, 'show']);
        Route::post('/', [AnnouncementController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [AnnouncementController::class, 'update']);
        Route::delete('/{id}', [AnnouncementController::class, 'destroy']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);
    });

    Route::get('/schedule/{day}', function ($day) {
        $response = Http::get('https://6a07932ffa9b27c848fa2d18.mockapi.io/Schedule');

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reach upstream external mock data system layer provider.'
            ], 502);
        }

        $schedules = collect($response->json());

        $filtered = $schedules->filter(function ($item) use ($day) {
            return strtolower($item['day']) == strtolower($day);
        });

        return response()->json($filtered->values());
    });

});