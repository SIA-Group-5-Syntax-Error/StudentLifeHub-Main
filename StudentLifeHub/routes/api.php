<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\TaskController;

// 1. Book Service (Local Search)
Route::get('/books/search', [BookController::class, 'search']);

// 2. Announcement Service (Proxying via RemoteGateway)
Route::prefix('announcements')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']); // Changed from apiAnnouncements to index
    Route::get('/{id}', [AnnouncementController::class, 'show']);
    Route::post('/', [AnnouncementController::class, 'store']);
    Route::match(['put', 'patch'], '/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/{id}', [AnnouncementController::class, 'destroy']);
});

// 3. Dictionary Service (External API Proxy)
Route::get('/dictionary/search', [DictionaryController::class, 'search']);

// 4. Todo/Task Service (MockAPI Proxy)
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});