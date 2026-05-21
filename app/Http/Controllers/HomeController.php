<?php

namespace App\Http\Controllers;

use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $booksService;

    public function __construct(GoogleBooksService $booksService)
    {
        $this->booksService = $booksService;
    }

    public function index()
    {
        // Fetch specific book for 'Current Read'
        $currentReadCover = $this->booksService->getBookCover('Laut Bercerita');
        $currentReadBook = \App\Models\Book::where('title', 'Laut Bercerita')->first();

        // Fetch Google Books ID for "Laut Bercerita" to enable linking to detail page
        $currentReadGoogleId = null;
        $currentReadResults = $this->booksService->searchBooks('intitle:Laut Bercerita', 1);
        if (!empty($currentReadResults)) {
            $currentReadGoogleId = $currentReadResults[0]['id'] ?? null;
        }

        // Fetch books for carousel (Popular This Week) - Using subject:fiction and newest
        $books = $this->booksService->searchBooks('subject:fiction', 10, 'newest');
        
        // Fetch recommendations for signed-in users - Using bestseller
        $recommendations = [];
        if (Auth::check()) {
            $recommendations = $this->booksService->searchBooks('bestseller', 10, 'relevance');
        }

        // Tampilkan view sesuai dengan status login
        if (Auth::check()) {
            return view('home_signed', compact('books', 'recommendations', 'currentReadCover', 'currentReadBook', 'currentReadGoogleId'));
        }
        
        return view('welcome', compact('books'));
    }
}

