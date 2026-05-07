<?php
 
namespace App\Http\Controllers;
 
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
    /**
     * Tampilkan form untuk menambah review.
     */
    public function create($book_id)
    {
        $book = Book::findOrFail($book_id);
        return view('books.add_review', compact('book'));
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

        Review::create([
            'user_id' => Auth::id() ?? 1, // Fallback ke ID 1 untuk testing
            'book_id' => $book_id,
            'rating' => $request->rating,
            'content' => $request->content,
            'songs' => $request->songs,
            'bookshelf_status' => $request->bookshelf_status,
        ]);

        return redirect()->back()->with('success', 'Review berhasil dikirim!');
    }
}
