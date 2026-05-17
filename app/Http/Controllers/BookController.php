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

    public function browse(Request $request)
    {
        $category = $request->input('category');
        
        if (empty($category)) {
            $category = 'fiction'; // default fallback
            $query = 'subject:' . $category;
        } else {
            $query = $category; // just use the raw keyword so searches like "hujan" work
        }
        
        // Fetching 50 results (40 + 10) to match search functionality
        $books1 = $this->googleBooksService->searchBooks($query, 40, 'relevance', 0);
        $books2 = $this->googleBooksService->searchBooks($query, 10, 'relevance', 40);
        $books = array_merge($books1, $books2);

        // Apply filters
        $genres = $request->input('genre', []);
        $publishFrom = $request->input('publish_from');
        $publishTo = $request->input('publish_to');
        $rating = $request->input('rating');

        if (!empty($genres) || $publishFrom || $publishTo || $rating) {
            $books = array_filter($books, function ($book) use ($genres, $publishFrom, $publishTo, $rating) {
                $volumeInfo = $book['volumeInfo'] ?? [];
                
                // Genre Filter
                if (!empty($genres)) {
                    $bookCategories = $volumeInfo['categories'] ?? [];
                    $genreMatch = false;
                    foreach ($genres as $genre) {
                        foreach ($bookCategories as $cat) {
                            if (stripos($cat, $genre) !== false) {
                                $genreMatch = true;
                                break 2;
                            }
                        }
                    }
                    if (!$genreMatch) return false;
                }
                
                // Publish Date Filter
                if ($publishFrom || $publishTo) {
                    $publishedDate = $volumeInfo['publishedDate'] ?? '';
                    $year = $publishedDate ? (int) substr($publishedDate, 0, 4) : 0;
                    if ($year === 0) return false;
                    
                    if ($publishFrom && $year < (int)$publishFrom) return false;
                    if ($publishTo && $year > (int)$publishTo) return false;
                }
                
                // Rating Filter
                if ($rating) {
                    $avgRating = $volumeInfo['averageRating'] ?? 0;
                    if ($avgRating < (float)$rating) return false;
                }
                
                return true;
            });
        }
        
        return view('books.browse', compact('books', 'category'));
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
