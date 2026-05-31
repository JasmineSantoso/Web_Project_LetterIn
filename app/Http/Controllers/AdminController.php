<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Bookshelf;
use App\Models\BannedUser;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    
    public function index()
    {
        // Totals
        $totalUsers = User::count();
        $totalReviews = Review::count();
        $totalBookshelves = Bookshelf::count();
        $totalReports = Report::count();

        // Labels for weekdays (Monday to Friday)
        $labels = ['Sen','Sel','Rab','Kam','Jum'];
        $dayMap = [2=>'Sen',3=>'Sel',4=>'Rab',5=>'Kam',6=>'Jum'];

        // Initialise arrays with zeros
        $userChartData = $reviewChartData = $bookshelfChartData = $reportChartData = array_fill_keys($labels, 0);

        // Helper closure to fill chart data from a model
        $fill = function($model, &$chart) use ($dayMap) {
            $model::selectRaw('DAYOFWEEK(created_at) as dow, COUNT(*) as cnt')
                ->where('created_at', '>=', now()->subDays(6))
                ->groupBy('dow')
                ->get()
                ->each(function($item) use (&$chart, $dayMap) {
                    if (isset($dayMap[$item->dow])) {
                        $chart[$dayMap[$item->dow]] = $item->cnt;
                    }
                });
        };

        // Populate each chart
        $fill(User::class, $userChartData);
        $fill(Review::class, $reviewChartData);
        $fill(Bookshelf::class, $bookshelfChartData);
        $fill(Report::class, $reportChartData);

        // Convert associative arrays to indexed arrays matching $labels order
        $userChartData = array_values($userChartData);
        $reviewChartData = array_values($reviewChartData);
        $bookshelfChartData = array_values($bookshelfChartData);
        $reportChartData = array_values($reportChartData);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalReviews',
            'totalBookshelves',
            'totalReports',
            'labels',
            'userChartData',
            'reviewChartData',
            'bookshelfChartData',
            'reportChartData'
        ));
    }

    /**
     * List all reports with status filters.
     */
    public function reports(Request $request)
    {
        $counts = [
            'all' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'compiled' => Report::whereIn('status', ['resolved', 'rejected'])->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        $status = $request->query('status', 'all');
        $query = Report::with(['reporter', 'reported', 'review']);

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'compiled') {
            $query->whereIn('status', ['resolved', 'rejected']);
        } elseif ($status === 'resolved') {
            $query->where('status', 'resolved');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reports.index', compact('reports', 'counts', 'status'));
    }

    /**
     * Show report details.
     */
    public function reportDetails($id)
    {
        $report = Report::with(['reporter', 'reported', 'review.book'])->findOrFail($id);

        $counts = [
            'all' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'compiled' => Report::whereIn('status', ['resolved', 'rejected'])->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        return view('admin.reports.show', compact('report', 'counts'));
    }

    /**
     * Resolve report (deletes review, keeps report log).
     */
    public function resolveReport($id)
    {
        $report = Report::findOrFail($id);

        // Delete the review if it still exists
        if ($report->review) {
            $report->review->delete();
        }

        // Update report status
        $report->update([
            'review_id' => null,
            'status' => 'resolved'
        ]);

        return redirect()->route('admin.reports')->with('success', 'Report resolved. Review was deleted and user warned.');
    }

    /**
     * Reject report (keeps review, marks report as rejected).
     */
    public function rejectReport($id)
    {
        $report = Report::findOrFail($id);

        // Update report status
        $report->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.reports')->with('success', 'Report rejected. Review will be kept.');
    }

    /**
     * List all users with filtering.
     */
    public function users(Request $request)
    {
        $status = $request->query('status', 'all');

        $activeCount = User::where('is_admin', false)->count();
        $bannedCount = BannedUser::count();
        
        $counts = [
            'all' => $activeCount + $bannedCount,
            'active' => $activeCount,
            'banned' => $bannedCount,
        ];

        if ($status === 'active') {
            $users = User::where('is_admin', false)
                ->withCount(['followers', 'following', 'reviews', 'bookshelves'])
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->through(function ($user) {
                    $user->is_banned_user = false;
                    $user->status_label = 'active';
                    return $user;
                });
        } elseif ($status === 'banned') {
            $users = BannedUser::orderBy('banned_at', 'desc')
                ->paginate(15)
                ->through(function ($banned) {
                    $banned->is_banned_user = true;
                    $banned->status_label = 'banned';
                    // map columns to standard names
                    $banned->created_at = $banned->registered_at;
                    return $banned;
                });
        } else {
            // Merge active and banned users in memory for 'all' status
            $activeUsers = User::where('is_admin', false)
                ->withCount(['followers', 'following', 'reviews', 'bookshelves'])
                ->get()
                ->map(function ($u) {
                    return (object)[
                        'user_id' => $u->user_id,
                        'username' => $u->username,
                        'fullname' => $u->fullname,
                        'email' => $u->email,
                        'profile' => $u->profile,
                        'followers_count' => $u->followers_count,
                        'following_count' => $u->following_count,
                        'reviews_count' => $u->reviews_count,
                        'bookshelves_count' => $u->bookshelves_count,
                        'registered_at' => $u->created_at,
                        'created_at' => $u->created_at,
                        'last_login_at' => $u->last_login_at,
                        'status_label' => 'active',
                        'is_banned_user' => false,
                    ];
                });

            $bannedUsers = BannedUser::all()
                ->map(function ($b) {
                    return (object)[
                        'user_id' => $b->user_id,
                        'username' => $b->username,
                        'fullname' => $b->fullname,
                        'email' => $b->email,
                        'profile' => null,
                        'followers_count' => $b->followers_count,
                        'following_count' => $b->following_count,
                        'reviews_count' => $b->reviews_count,
                        'bookshelves_count' => $b->bookshelves_count,
                        'registered_at' => $b->registered_at,
                        'created_at' => $b->registered_at,
                        'last_login_at' => $b->last_login_at,
                        'status_label' => 'banned',
                        'is_banned_user' => true,
                    ];
                });

            $merged = $activeUsers->concat($bannedUsers)->sortByDesc('created_at');

            // Paginate manually
            $page = $request->query('page', 1);
            $perPage = 15;
            $sliced = $merged->slice(($page - 1) * $perPage, $perPage)->values();
            
            $users = new \Illuminate\Pagination\LengthAwarePaginator(
                $sliced,
                $merged->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('admin.users.index', compact('users', 'counts', 'status'));
    }

    /**
     * Show details of a specific user.
     */
    public function userDetails(Request $request, $id)
    {
        $type = $request->query('type', 'active');

        if ($type === 'banned') {
            $user = BannedUser::where('user_id', $id)->firstOrFail();
            $user->is_banned_user = true;
            $user->status_label = 'banned';
            $user->created_at = $user->registered_at;
        } else {
            $user = User::where('user_id', $id)
                ->withCount(['followers', 'following', 'reviews', 'bookshelves'])
                ->firstOrFail();
            $user->is_banned_user = false;
            $user->status_label = 'active';
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Ban a specific active user.
     */
    public function banUser(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        $reason = $request->input('ban_reason', 'No reason provided.');

        // Get counts before deletion
        $followingCount = $user->following()->count();
        $followersCount = $user->followers()->count();
        $reviewsCount = $user->reviews()->count();
        $bookshelvesCount = $user->bookshelves()->count();

        // Save into banned_users table
        BannedUser::create([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'following_count' => $followingCount,
            'followers_count' => $followersCount,
            'reviews_count' => $reviewsCount,
            'bookshelves_count' => $bookshelvesCount,
            'registered_at' => $user->created_at,
            'last_login_at' => $user->last_login_at,
            'ban_reason' => $reason,
        ]);

        // Delete the active user (this will cascade delete all related database records)
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User @' . $user->username . ' has been suspended and details archived.');
    }

    /**
     * Permanently delete a user (active or banned).
     */
    public function deleteUser(Request $request, $id)
    {
        $type = $request->query('type', 'active');

        if ($type === 'banned') {
            $user = BannedUser::where('user_id', $id)->firstOrFail();
            $username = $user->username;
            $user->delete();
        } else {
            $user = User::where('user_id', $id)->firstOrFail();
            $username = $user->username;
            $user->delete(); // cascades related data
        }

        return redirect()->route('admin.users')->with('success', 'User @' . $username . ' has been permanently deleted.');
    }

    /**
     * List all reviews for moderation.
     */
    public function reviews()
    {
        $reviews = Review::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show details of a specific review for moderation.
     */
    public function reviewDetails($id)
    {
        $review = Review::with(['user', 'book'])
            ->withCount(['likes', 'comments', 'reports'])
            ->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Delete a review.
     */
    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews')->with('success', 'Ulasan berhasil dihapus.');
    }
}

