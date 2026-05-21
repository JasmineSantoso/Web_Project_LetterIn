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
        $category = $request->input('category') ?? $request->input('q');
        $genres = $request->input('genre', []);
        $publishFrom = $request->input('publish_from');
        $publishTo = $request->input('publish_to');
        $rating = $request->input('rating');

        // Build a highly optimized and accurate query for Google Books API based on category & selected genres
        $queryParts = [];
        if (!empty($category)) {
            $queryParts[] = $category;
        }

        if (!empty($genres)) {
            foreach ($genres as $genre) {
                // e.g. subject:Romance
                $queryParts[] = 'subject:' . $genre;
            }
        }

        if (empty($queryParts)) {
            // Default query fallback if nothing is chosen
            $query = 'subject:fiction';
        } else {
            $query = implode(' ', $queryParts);
        }

        // Fetching up to 50 books from the Google Books API
        $books1 = $this->googleBooksService->searchBooks($query, 40, 'relevance', 0);
        $books2 = $this->googleBooksService->searchBooks($query, 10, 'relevance', 40);
        $books = array_merge($books1, $books2);

        // Robust Fallback: If network is offline, cURL times out, or API quota is exceeded
        if (empty($books)) {
            $books = $this->getFallbackBooks();
        }

        // Double Filter (Post-API logic) to guarantee precise match for user requests
        if (!empty($genres) || $publishFrom || $publishTo || $rating) {
            $books = array_filter($books, function ($book) use ($genres, $publishFrom, $publishTo, $rating) {
                $volumeInfo = $book['volumeInfo'] ?? [];
                
                // 1. Genre filter check (Only apply to local fallback books, trust API subject: query for API books)
                if (!empty($genres)) {
                    $isDummy = str_contains($book['id'] ?? '', '-dummy') || in_array($book['id'] ?? '', ['fDKxMr5Md3QC', 'zyTCAlFPjgJC']);
                    if (!$isDummy) {
                        // Google Books API has already filtered this by genre using "subject:Genre" in the API request!
                        $genreMatch = true;
                    } else {
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
                    }
                    if (!$genreMatch) return false;
                }
                
                // 2. Publication date filter check
                if ($publishFrom || $publishTo) {
                    $publishedDate = $volumeInfo['publishedDate'] ?? '';
                    $year = $publishedDate ? (int) substr($publishedDate, 0, 4) : 0;
                    if ($year === 0) return false;
                    
                    if ($publishFrom && $year < (int)$publishFrom) return false;
                    if ($publishTo && $year > (int)$publishTo) return false;
                }
                
                // 3. Rating filter check
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
        $book = null;
        if (is_numeric($id)) {
            $localBook = \App\Models\Book::find($id);
            if ($localBook) {
                $book = [
                    'id' => $localBook->id,
                    'volumeInfo' => [
                        'title' => $localBook->title,
                        'authors' => [$localBook->author],
                        'description' => 'A wonderful book read and reviewed on LetterIn.',
                        'categories' => ['Fiction'],
                        'imageLinks' => [
                            'thumbnail' => (str_starts_with($localBook->cover_image ?? '', 'http') || empty($localBook->cover_image)) ? ($localBook->cover_image ?: asset('images/image11.jpg')) : asset('images/' . $localBook->cover_image)
                        ],
                        'printType' => 'BOOK',
                        'language' => 'id',
                        'publisher' => 'Local Bookshelf',
                        'publishedDate' => $localBook->created_at ? $localBook->created_at->format('Y-m-d') : 'Unknown'
                    ]
                ];
            }
        }

        if (!$book) {
            $book = $this->googleBooksService->getBookById($id);
        }
        
        if (!$book) {
            // Robust fallback: If Google Books API fails/times out, render a nice placeholder book data
            $fallbackList = $this->getFallbackBooks();
            $found = null;
            foreach ($fallbackList as $fBook) {
                if ($fBook['id'] === $id) {
                    $found = $fBook;
                    break;
                }
            }

            $book = $found ?? [
                'volumeInfo' => [
                    'title' => 'Hujan (Fallback - API Limit/Offline)',
                    'subtitle' => 'Persahabatan, Cinta, dan Melupakan',
                    'authors' => ['Tere Liye'],
                    'publisher' => 'Gramedia Pustaka Utama',
                    'publishedDate' => '16 April 2018',
                    'description' => 'Tentang persahabatan... Tentang cinta... Tentang melupakan... Tentang perpisahan... Dan tentang hujan... <br><br><i>(Catatan: Detail buku ini ditampilkan menggunakan data cadangan karena koneksi ke Google Books API mengalami timeout/terputus).</i>',
                    'pageCount' => 318,
                    'averageRating' => 4.5,
                    'ratingsCount' => 842,
                    'categories' => ['Fiction', 'Romance'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'maturityRating' => 'NOT_MATURE',
                    'imageLinks' => [
                        'thumbnail' => asset('images/hujan1.jpg')
                    ]
                ]
            ];
        }

        $isFavorited = false;
        if (auth()->check()) {
            $localBook = \App\Models\Book::where('google_id', $id)->first();
            if ($localBook) {
                $isFavorited = auth()->user()->favoriteBooks()->where('books.id', $localBook->id)->exists();
            }
        }

        return view('books.details', compact('id', 'book', 'isFavorited'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $books = [];

        if ($query) {
            $books1 = $this->googleBooksService->searchBooks($query, 40, 'relevance', 0);
            $books2 = $this->googleBooksService->searchBooks($query, 10, 'relevance', 40);
            $books = array_merge($books1, $books2);

            // Fallback for search
            if (empty($books)) {
                $allFallbacks = $this->getFallbackBooks();
                $books = array_filter($allFallbacks, function ($b) use ($query) {
                    $title = $b['volumeInfo']['title'] ?? '';
                    $authors = implode(' ', $b['volumeInfo']['authors'] ?? []);
                    return stripos($title, $query) !== false || stripos($authors, $query) !== false;
                });
            }
        }

        return view('books.search', [
            'books' => $books,
            'query' => $query,
            'forceGuestHeader' => false
        ]);
    }

    public function addReview($id)
    {
        return view('books.add_review', compact('id'));
    }

    public function toggleFavorite(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Find the book locally or fetch and save it
        $book = \App\Models\Book::where('google_id', $id)->first();
        if (!$book) {
            $googleBook = $this->googleBooksService->getBookById($id);
            if (!$googleBook) {
                return response()->json(['success' => false, 'message' => 'Book not found'], 404);
            }
            
            $volumeInfo = $googleBook['volumeInfo'];
            $title = $volumeInfo['title'] ?? 'Unknown Title';
            $author = !empty($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Unknown Author';
            $coverImage = $volumeInfo['imageLinks']['thumbnail'] ?? null;

            $book = \App\Models\Book::create([
                'google_id' => $id,
                'title' => $title,
                'author' => $author,
                'cover_image' => $coverImage,
            ]);
        }

        $isFavorited = $user->favoriteBooks()->where('books.id', $book->id)->exists();

        if ($isFavorited) {
            $user->favoriteBooks()->detach($book->id);
            $action = 'removed';
        } else {
            $user->favoriteBooks()->attach($book->id);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => 'Book ' . $action . ' from favorites'
        ]);
    }

    /**
     * Get local fallback high-quality books when API is offline/rate-limited
     */
    private function getFallbackBooks()
    {
        return [
            [
                'id' => 'fDKxMr5Md3QC', // Hujan
                'volumeInfo' => [
                    'title' => 'Hujan',
                    'authors' => ['Tere Liye'],
                    'publisher' => 'Gramedia Pustaka Utama',
                    'publishedDate' => '2016-01-01',
                    'description' => 'Tentang persahabatan... Tentang cinta... Tentang melupakan... Tentang perpisahan... Dan tentang hujan...',
                    'pageCount' => 318,
                    'averageRating' => 4.5,
                    'ratingsCount' => 842,
                    'categories' => ['Fiction', 'Romance'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'imageLinks' => [
                        'thumbnail' => asset('images/hujan1.jpg')
                    ]
                ]
            ],
            [
                'id' => 'zyTCAlFPjgJC', // Bumi
                'volumeInfo' => [
                    'title' => 'Bumi',
                    'authors' => ['Tere Liye'],
                    'publisher' => 'Gramedia Pustaka Utama',
                    'publishedDate' => '2014-01-01',
                    'description' => 'Nama saya Raib, usia 15 tahun, siswa kelas X. Saya mempunyai dua kucing, namanya Si Putih dan Si Hitam. Saya bisa menghilang...',
                    'pageCount' => 440,
                    'averageRating' => 4.3,
                    'ratingsCount' => 712,
                    'categories' => ['Fiction', 'Fantasy'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'imageLinks' => [
                        'thumbnail' => asset('images/cover1.jpg')
                    ]
                ]
            ],
            [
                'id' => 'laskar-pelangi-dummy',
                'volumeInfo' => [
                    'title' => 'Laskar Pelangi',
                    'authors' => ['Andrea Hirata'],
                    'publisher' => 'Bentang Pustaka',
                    'publishedDate' => '2005-09-01',
                    'description' => 'Sebuah novel luar biasa tentang sekumpulan anak-anak pejuang mimpi di Pulau Belitung.',
                    'pageCount' => 529,
                    'averageRating' => 4.8,
                    'ratingsCount' => 1250,
                    'categories' => ['Fiction', 'History'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'imageLinks' => [
                        'thumbnail' => asset('images/cover2.jpg')
                    ]
                ]
            ],
            [
                'id' => 'cantik-itu-luka-dummy',
                'volumeInfo' => [
                    'title' => 'Cantik Itu Luka',
                    'authors' => ['Eka Kurniawan'],
                    'publisher' => 'Gramedia Pustaka Utama',
                    'publishedDate' => '2002-03-01',
                    'description' => 'Satu sore di akhir pekan di bulan Maret, Dewi Ayu bangkit dari kuburnya setelah dua puluh satu tahun kematian...',
                    'pageCount' => 508,
                    'averageRating' => 4.2,
                    'ratingsCount' => 510,
                    'categories' => ['Fiction', 'Mystery'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'imageLinks' => [
                        'thumbnail' => asset('images/cover3.jpg')
                    ]
                ]
            ],
            [
                'id' => 'biography-dummy',
                'volumeInfo' => [
                    'title' => 'Habibie & Ainun',
                    'authors' => ['B.J. Habibie'],
                    'publisher' => 'THC Mandiri',
                    'publishedDate' => '2010-11-01',
                    'description' => 'Kisah cinta abadi antara Presiden RI ketiga, BJ Habibie, dan sang istri tercinta, Ainun.',
                    'pageCount' => 323,
                    'averageRating' => 4.7,
                    'ratingsCount' => 950,
                    'categories' => ['Biography', 'Romance'],
                    'printType' => 'BOOK',
                    'language' => 'id',
                    'imageLinks' => [
                        'thumbnail' => asset('images/cover4.jpg')
                    ]
                ]
            ]
        ];
    }
}
