<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Bookshelf;

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
}
