<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleBooksService;

class BookController extends Controller
{
    protected $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    public function browse()
    {
        return view('books.browse');
    }

    public function details($id)
    {
        return view('books.details', compact('id'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $books = [];

        if ($query) {
            // Fetching 50 results (40 + 10)
            $books1 = $this->googleBooksService->searchBooks($query, 40, 'relevance', 0);
            $books2 = $this->googleBooksService->searchBooks($query, 10, 'relevance', 40);
            $books = array_merge($books1, $books2);
        }

        return view('books.search', [
            'books' => $books,
            'query' => $query,
            'forceGuestHeader' => true
        ]);
    }

    public function addReview($id)
    {
        return view('books.add_review', compact('id'));
    }
}
