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

    /**
     * Get cover image for a specific book title
     */
    public function getBookCover($title)
    {
        $books = $this->searchBooks('intitle:' . $title, 1);
        
        if (!empty($books)) {
            $volumeInfo = $books[0]['volumeInfo'] ?? [];
            return $volumeInfo['imageLinks']['thumbnail'] ?? null;
        }

        return null;
    }
    /**
     * Get book details by ID
     */
    public function getBookById($id)
    {
        $params = [];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        $response = Http::withoutVerifying()->get($this->baseUrl . '/' . $id, $params);

        if ($response->successful()) {
            return $response->json();
        }

        // Return fallback dummy data if API fails (e.g., quota exceeded)
        return [
            'id' => $id,
            'volumeInfo' => [
                'title' => 'Hujan (Dummy - API Quota Exceeded)',
                'authors' => ['Tere Liye'],
                'publisher' => 'Gramedia Pustaka Utama',
                'publishedDate' => '2016',
                'description' => 'Tentang persahabatan... Tentang cinta... Tentang melupakan... Tentang perpisahan... Dan tentang hujan... <br><br><i>Note: Ini adalah data sementara karena limit/kuota Google Books API Anda sedang habis.</i>',
                'pageCount' => 318,
                'language' => 'id',
            ]
        ];
    }
}
