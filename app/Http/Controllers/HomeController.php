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
        $currentReadBook = null;
        $currentReadCover = null;
        $currentReadGoogleId = null;
        $currentReadPercent = 0;
        $currentReadStartDate = null;
        $currentReadRating = 4.5;

        if (Auth::check()) {
            $user = Auth::user();
            $latestReading = \DB::table('user_book_statuses')
                ->join('books', 'user_book_statuses.book_id', '=', 'books.id')
                ->where('user_book_statuses.user_id', $user->user_id)
                ->where('user_book_statuses.status', 'currently_reading')
                ->select('books.*', 'user_book_statuses.progress_percent', 'user_book_statuses.start_date')
                ->orderBy('user_book_statuses.updated_at', 'desc')
                ->first();

            if ($latestReading) {
                $currentReadBook = $latestReading;
                $currentReadCover = (str_starts_with($latestReading->cover_image ?? '', 'http') || empty($latestReading->cover_image)) ? ($latestReading->cover_image ?: asset('images/cover1.jpg')) : $latestReading->cover_image;
                $currentReadGoogleId = $latestReading->google_id;
                $currentReadPercent = $latestReading->progress_percent;
                $currentReadStartDate = $latestReading->start_date ? \Carbon\Carbon::parse($latestReading->start_date)->format('d-m-Y') : null;

                $localAverageRating = \App\Models\Review::where('book_id', $latestReading->id)->avg('rating');
                if ($localAverageRating) {
                    $currentReadRating = $localAverageRating;
                } else {
                    try {
                        if ($latestReading->google_id) {
                            $apiBook = $this->booksService->getBookById($latestReading->google_id);
                            $currentReadRating = $apiBook['volumeInfo']['averageRating'] ?? 4.5;
                        }
                    } catch (\Exception $e) {
                        $currentReadRating = 4.5;
                    }
                }
            }
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
            return view('home_signed', compact('books', 'recommendations', 'currentReadCover', 'currentReadBook', 'currentReadGoogleId', 'currentReadPercent', 'currentReadStartDate', 'currentReadRating'));
        }
        
        return view('welcome', compact('books'));
    }
}

