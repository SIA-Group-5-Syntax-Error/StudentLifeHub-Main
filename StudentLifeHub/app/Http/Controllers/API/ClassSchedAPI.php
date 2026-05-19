<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/schedule/{day}', function ($day) {

    $response = Http::get('https://6a07932ffa9b27c848fa2d18.mockapi.io/Schedule');

    $schedules = collect($response->json());

    $filtered = $schedules->filter(function ($item) use ($day) {
        return strtolower($item['day']) == strtolower($day);
    });

    return response()->json($filtered->values());

});