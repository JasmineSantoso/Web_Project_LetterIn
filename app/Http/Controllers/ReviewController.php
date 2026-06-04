<?php
 
namespace App\Http\Controllers;
 
use App\Models\Book;
use App\Models\Review;
use App\Models\ReviewLike;
use App\Models\ReviewComment;
use App\Models\ReviewReport;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    protected $googleBooksService;

    public function __construct(\App\Services\GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    /**
     * Tampilkan form untuk menambah review.
     */
    public function create($book_id)
    {
        $book = null;
        $genre = 'Fiction';

        // 1. Resolve book from local DB or Google Books API
        if (!is_numeric($book_id)) {
            // It's a Google Books volume ID
            $googleBooksService = app(\App\Services\GoogleBooksService::class);
            $bookData = $googleBooksService->getBookById($book_id);

            if ($bookData) {
                $volumeInfo = $bookData['volumeInfo'] ?? [];
                $title = $volumeInfo['title'] ?? 'Unknown Title';
                $authors = implode(', ', $volumeInfo['authors'] ?? ['Unknown Author']);
                
                $cover = null;
                if (isset($volumeInfo['imageLinks'])) {
                    $cover = $volumeInfo['imageLinks']['thumbnail']
                          ?? $volumeInfo['imageLinks']['smallThumbnail']
                          ?? null;
                }

                // Parse genre/category
                if (isset($volumeInfo['categories']) && !empty($volumeInfo['categories'])) {
                    $genre = $volumeInfo['categories'][0];
                }

                // Check if book exists in local DB, otherwise save it
                $book = Book::where('google_id', $book_id)
                    ->orWhere(function ($query) use ($title, $authors) {
                        $query->where('title', $title)->where('author', $authors);
                    })->first();

                if (!$book) {
                    $book = Book::create([
                        'google_id' => $book_id,
                        'title' => $title,
                        'author' => $authors,
                        'cover_image' => $cover
                    ]);
                } else if (empty($book->google_id)) {
                    $book->google_id = $book_id;
                    $book->save();
                }
            } else {
                abort(404, 'Book not found on Google Books API');
            }
        } else {
            // It's a local integer ID
            $book = Book::findOrFail($book_id);
            if ($book->title === 'Laut Bercerita') {
                $genre = 'Drama';
            }
        }

        // 2. Fetch 3 recommended songs based on the book's genre using Deezer Search API
        $recommendedSongs = [];
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://api.deezer.com/search', [
                    'q' => $genre,
                    'limit' => 5
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $count = 0;
                foreach ($data as $item) {
                    if ($count >= 3) break;
                    
                    $songTitle = $item['title'] ?? '';
                    $artistName = $item['artist']['name'] ?? '';
                    
                    // Skip if title or artist is empty
                    if (empty($songTitle) || empty($artistName)) continue;

                    $recommendedSongs[] = [
                        'title' => $songTitle,
                        'artist' => $artistName,
                        'album_art' => $item['album']['cover_medium'] ?? ($item['album']['cover_small'] ?? ''),
                        'preview_url' => $item['preview'] ?? ''
                    ];
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Log::error('Deezer recommendations failed: ' . $e->getMessage());
        }

        // Fallback recommendations matching the screenshot exactly if API is rate-limited or offline
        if (empty($recommendedSongs)) {
            $recommendedSongs = [
                [
                    'title' => 'Daylight',
                    'artist' => 'Harry Style',
                    'album_art' => asset('images/cover2.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3'
                ],
                [
                    'title' => 'Love Notes',
                    'artist' => 'Olivia D.',
                    'album_art' => asset('images/cover4.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3'
                ],
                [
                    'title' => 'Dear Reader',
                    'artist' => 'Taylor S.',
                    'album_art' => asset('images/cover3.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3'
                ]
            ];
        }

        $userBookshelves = Auth::check() ? Auth::user()->bookshelves()->get() : collect();
        return view('books.add_review', compact('book', 'recommendedSongs', 'book_id', 'userBookshelves'));
    }

    /**
     * Simpan review baru ke database.
     */
    public function store(Request $request, $book_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'bookshelf_status' => 'nullable|string',
            'songs' => 'nullable|array',
        ]);

        $resolvedBookId = $book_id;
        if (!is_numeric($book_id)) {
            // If the route still used the string ID, we look up the book by its title/author or find it
            $googleBooksService = app(\App\Services\GoogleBooksService::class);
            $bookData = $googleBooksService->getBookById($book_id);
            if ($bookData) {
                $volumeInfo = $bookData['volumeInfo'] ?? [];
                $title = $volumeInfo['title'] ?? '';
                $authors = implode(', ', $volumeInfo['authors'] ?? []);
                $localBook = Book::where('google_id', $book_id)
                    ->orWhere(function($q) use ($title, $authors) {
                        $q->where('title', $title)->where('author', $authors);
                    })->first();
                if ($localBook) {
                    if (empty($localBook->google_id)) {
                        $localBook->google_id = $book_id;
                        $localBook->save();
                    }
                    $resolvedBookId = $localBook->id;
                } else {
                    $cover = null;
                    if (isset($volumeInfo['imageLinks'])) {
                        $cover = $volumeInfo['imageLinks']['thumbnail']
                              ?? $volumeInfo['imageLinks']['smallThumbnail']
                              ?? null;
                    }
                    $localBook = Book::create([
                        'google_id' => $book_id,
                        'title' => $title,
                        'author' => $authors,
                        'cover_image' => $cover
                    ]);
                    $resolvedBookId = $localBook->id;
                }
            }
        }

        Review::create([
            'user_id' => Auth::id() ?? 1, // Fallback to ID 1 for testing
            'book_id' => $resolvedBookId,
            'rating' => $request->rating,
            'content' => $request->content,
            'songs' => $request->songs,
            'bookshelf_status' => $request->bookshelf_status,
        ]);

        return redirect()->route('book.details', ['id' => $book_id])->with('success', 'Review berhasil dikirim!');
    }

    /**
     * Deezer Search API proxy to bypass CORS
     */
    public function searchDeezer(Request $request)
    {
        $query = $request->query('q');
        if (empty($query)) {
            return response()->json(['data' => []]);
        }

        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://api.deezer.com/search', [
                    'q' => $query
                ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }
        } catch (\Exception $e) {
            Log::error('Deezer search proxy exception: ' . $e->getMessage());
        }

        return response()->json(['error' => 'Failed to connect to Deezer API'], 500);
    }

    /**
     * Toggle like on a review.
     */
    public function toggleLike($id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $like = ReviewLike::where('review_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $action = 'unliked';
        } else {
            ReviewLike::create([
                'review_id' => $id,
                'user_id' => $userId,
            ]);
            $action = 'liked';
        }

        $likesCount = ReviewLike::where('review_id', $id)->count();

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $likesCount,
        ]);
    }

    /**
     * Store comment on a review.
     */
    public function storeComment(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = ReviewComment::create([
            'review_id' => $id,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        // Eager load user relationship with the newly created comment
        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => [
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'username' => $comment->user->username,
                    'fullname' => $comment->user->fullname,
                    'profile' => $comment->user->profile ? asset('images/' . $comment->user->profile) : null,
                    'profile_url' => route('profile.show', ['username' => $comment->user->username]),
                ]
            ]
        ]);
    }

    /**
     * Store report on a review.
     */
    public function report(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        // Check if already reported by this user to prevent duplicate reports
        $existingReport = ReviewReport::where('review_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melaporkan review ini.'
            ]);
        }

        $review = Review::findOrFail($id);

        ReviewReport::create([
            'review_id' => $id,
            'user_id' => $userId,
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        Report::create([
            'reporter_id' => $userId,
            'reported_id' => $review->user_id,
            'review_id' => $id,
            'category' => $request->reason,
            'content' => $request->details,
            'reported_review_text' => $review->content,
            'reported_review_rating' => $review->rating,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim. Terima kasih atas masukan Anda.'
        ]);
    }

    /**
     * Tampilkan form untuk mengedit review.
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        // Pastikan hanya pembuat review yang bisa mengedit
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $book = $review->book;
        $genre = 'Fiction';

        // Resolve genre/category dari volumeInfo Google Books atau local data
        if ($book && $book->google_id) {
            $googleBooksService = app(\App\Services\GoogleBooksService::class);
            $bookData = $googleBooksService->getBookById($book->google_id);
            if ($bookData) {
                $volumeInfo = $bookData['volumeInfo'] ?? [];
                if (isset($volumeInfo['categories']) && !empty($volumeInfo['categories'])) {
                    $genre = $volumeInfo['categories'][0];
                }
            }
        } else if ($book && $book->title === 'Laut Bercerita') {
            $genre = 'Drama';
        }

        // Fetch recommended songs
        $recommendedSongs = [];
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get('https://api.deezer.com/search', [
                    'q' => $genre,
                    'limit' => 5
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $count = 0;
                foreach ($data as $item) {
                    if ($count >= 3) break;
                    
                    $songTitle = $item['title'] ?? '';
                    $artistName = $item['artist']['name'] ?? '';
                    
                    if (empty($songTitle) || empty($artistName)) continue;

                    $recommendedSongs[] = [
                        'title' => $songTitle,
                        'artist' => $artistName,
                        'album_art' => $item['album']['cover_medium'] ?? ($item['album']['cover_small'] ?? ''),
                        'preview_url' => $item['preview'] ?? ''
                    ];
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Log::error('Deezer recommendations failed: ' . $e->getMessage());
        }

        if (empty($recommendedSongs)) {
            $recommendedSongs = [
                [
                    'title' => 'Daylight',
                    'artist' => 'Harry Style',
                    'album_art' => asset('images/cover2.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3'
                ],
                [
                    'title' => 'Love Notes',
                    'artist' => 'Olivia D.',
                    'album_art' => asset('images/cover4.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3'
                ],
                [
                    'title' => 'Dear Reader',
                    'artist' => 'Taylor S.',
                    'album_art' => asset('images/cover3.jpg'),
                    'preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3'
                ]
            ];
        }

        $userBookshelves = Auth::check() ? Auth::user()->bookshelves()->get() : collect();
        return view('books.edit_review', compact('review', 'book', 'recommendedSongs', 'userBookshelves'));
    }

    /**
     * Update review di database.
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Pastikan hanya pembuat review yang bisa mengupdate
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'bookshelf_status' => 'nullable|string',
            'songs' => 'nullable|array',
        ]);

        $review->update([
            'rating' => $request->rating,
            'content' => $request->content,
            'songs' => $request->songs,
            'bookshelf_status' => $request->bookshelf_status,
        ]);

        $bookId = $review->book->google_id ?? $review->book->id;

        return redirect()->route('book.details', ['id' => $bookId])->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Hapus review dari database.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Pastikan hanya pembuat review yang bisa menghapus
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus!');
    }
}
