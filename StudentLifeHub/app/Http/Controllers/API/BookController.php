<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class BookController extends Controller
{
    public function search(Request $request)
    {
        // Require the title parameter
        $request->validate([
            'title' => 'required|string|min:1'
        ]);

        $title = $request->query('title');

        // Search only by title
        $books = Book::where('title', 'LIKE', "%{$title}%")->get();

        return response()->json([
            'service' => 'book-search-api',
            'results' => $books
        ]);
    }
}