<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'q'          => $query,
            'maxResults' => $maxResults,
            'orderBy'    => $orderBy,
            'startIndex' => $startIndex,
        ];

        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        try {
            $response = Http::withoutVerifying()->timeout(10)->get($this->baseUrl, $params);

            if ($response->successful()) {
                return $response->json()['items'] ?? [];
            }

            Log::warning('GoogleBooksService::searchBooks failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('GoogleBooksService::searchBooks exception: ' . $e->getMessage());
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

    /**
     * Get a single book's full data by Google Books volume ID
     */
    public function getBookById($id)
    {
        if (empty($id)) {
            return null;
        }

        $params = [];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        try {
            $url = $this->baseUrl . '/' . urlencode($id);
            $response = Http::withoutVerifying()->timeout(10)->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();

                // Upgrade thumbnail to higher-res if available
                if (isset($data['volumeInfo']['imageLinks'])) {
                    $links = $data['volumeInfo']['imageLinks'];
                    $best  = $links['extraLarge']
                          ?? $links['large']
                          ?? $links['medium']
                          ?? $links['small']
                          ?? $links['thumbnail']
                          ?? null;

                    // Force HTTPS
                    if ($best) {
                        $best = str_replace('http://', 'https://', $best);
                        $data['volumeInfo']['imageLinks']['thumbnail'] = $best;
                    }
                }

                return $data;
            }

            Log::warning('GoogleBooksService::getBookById failed', [
                'id'     => $id,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('GoogleBooksService::getBookById exception: ' . $e->getMessage());
        }

        return null;
    }
}
