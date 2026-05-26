<?php

namespace App\Http\Controllers\Services\API;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class BookController extends Controller
{
    public function search(Request $request)
    {
        // 1. Require the title query parameter
        $request->validate([
            'title' => 'required|string|min:1'
        ]);

        $title = $request->query('title');

        // 2. Query the local MySQL books table rows directly
        $books = Book::where('title', 'LIKE', "%{$title}%")->get();

        // 3. Return the standard local response structure
        return response()->json([
            'service' => 'book-search-api',
            'results' => $books
        ], 200, [], JSON_PRETTY_PRINT);
    }
}