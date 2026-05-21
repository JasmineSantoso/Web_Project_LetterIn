<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookshelf;
use App\Models\Book;
use App\Services\GoogleBooksService;

class BookshelfController extends Controller
{
    protected $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    /**
     * Return user's bookshelves as JSON (for AJAX calls).
     */
    public function index()
    {
        $shelves = auth()->user()->bookshelves()->withCount('books')->get();
        return response()->json($shelves);
    }

    /**
     * Create a new bookshelf for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $shelf = Bookshelf::create([
            'user_id' => auth()->id(),
            'name'    => trim($request->name),
        ]);

        // If a book_google_id is passed, add the book immediately after creation
        if ($request->filled('book_google_id')) {
            $book = $this->findOrCreateBook($request->book_google_id);
            if ($book) {
                $shelf->books()->syncWithoutDetaching([$book->id]);
            }
        }

        return response()->json([
            'success' => true,
            'shelf'   => $shelf->loadCount('books'),
            'message' => 'Shelf "' . $shelf->name . '" created!',
        ]);
    }

    /**
     * Add a book to an existing bookshelf.
     */
    public function addBook(Request $request, $shelfId)
    {
        $request->validate([
            'book_google_id' => 'required|string',
        ]);

        $shelf = Bookshelf::where('id', $shelfId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $book = $this->findOrCreateBook($request->book_google_id);
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        $alreadyIn = $shelf->books()->where('books.id', $book->id)->exists();
        if ($alreadyIn) {
            return response()->json(['success' => false, 'message' => 'Book already in this shelf.'], 409);
        }

        $shelf->books()->attach($book->id);

        return response()->json([
            'success' => true,
            'message' => '"' . $book->title . '" added to "' . $shelf->name . '"!',
        ]);
    }

    /**
     * Remove a book from a bookshelf.
     */
    public function removeBook(Request $request, $shelfId, $bookId)
    {
        $shelf = Bookshelf::where('id', $shelfId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $shelf->books()->detach($bookId);

        return response()->json(['success' => true, 'message' => 'Book removed from shelf.']);
    }

    /**
     * Delete an entire bookshelf.
     */
    public function destroy($shelfId)
    {
        $shelf = Bookshelf::where('id', $shelfId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $shelf->delete();

        return response()->json(['success' => true, 'message' => 'Shelf deleted.']);
    }

    /**
     * Get the list of books in a shelf.
     */
    public function booksList($shelfId)
    {
        $shelf = Bookshelf::where('id', $shelfId)
            ->where('user_id', auth()->id())
            ->with('books')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'books'   => $shelf->books,
        ]);
    }

    /**
     * Find existing book by google_id or create a new one from Google Books API.
     */
    private function findOrCreateBook($googleId)
    {
        $book = Book::where('google_id', $googleId)->first();
        if ($book) {
            return $book;
        }

        $googleBook = $this->googleBooksService->getBookById($googleId);
        if (!$googleBook) {
            return null;
        }

        $volumeInfo = $googleBook['volumeInfo'];
        return Book::create([
            'google_id'   => $googleId,
            'title'       => $volumeInfo['title'] ?? 'Unknown Title',
            'author'      => implode(', ', $volumeInfo['authors'] ?? ['Unknown Author']),
            'cover_image' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
        ]);
    }
}
