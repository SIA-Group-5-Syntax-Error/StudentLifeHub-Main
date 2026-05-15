<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DictionaryController extends Controller
{
    public function search(Request $request)
    {
        $word = $request->query('word');

        // If no word entered
        if (!$word) {
            return response()->json([
                'status' => 'error',
                'message' => 'No word provided'
            ], 400, [], JSON_PRETTY_PRINT);
        }

        // Call external dictionary API
        $response = Http::get("https://api.dictionaryapi.dev/api/v2/entries/en/" . $word);

        // If word not found
        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Word not found'
            ], 404, [], JSON_PRETTY_PRINT);
        }

        $data = $response->json();
        $definition = $data[0]['meanings'][0]['definitions'][0]['definition'] ?? 'No definition available';

        // Extract useful info
        return response()->json([
            'status' => 'success',
            'word' => $word,
            'definition' => $definition,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}