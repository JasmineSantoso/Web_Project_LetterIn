<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected $apiKey;
    protected $baseUrl = 'https://www.googleapis.com/books/v1/volumes';

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_BOOKS_API');
    }

    /**
     * Search books by query
     */
    public function searchBooks($query, $maxResults = 10, $orderBy = 'relevance', $startIndex = 0)
    {
        $params = [
            'q' => $query,
            'maxResults' => $maxResults,
            'orderBy' => $orderBy,
            'startIndex' => $startIndex,
        ];

        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        $response = Http::withoutVerifying()->get($this->baseUrl, $params);

        if ($response->successful()) {
            return $response->json()['items'] ?? [];
        }

        return [];
    }

    public function getBookCover($title)
    {
        $books = $this->searchBooks('intitle:' . $title, 1);
        
        if (!empty($books)) {
            $volumeInfo = $books[0]['volumeInfo'] ?? [];
            return $volumeInfo['imageLinks']['thumbnail'] ?? null;
        }

        return null;
    }
    public function getBookById($id)
    {
        $params = [];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        try {
            $response = Http::withoutVerifying()->timeout(10)->get($this->baseUrl . '/' . $id, $params);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // Log the error or handle it, but for now just fall through to return null
        }

        return null;
    }
}
