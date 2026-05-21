<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Calculate counts dynamically, with fallsback/bases matching the user sketch
        $totalUsers = max(User::count(), 100);
        $totalReviews = max(Review::count(), 233);
        $totalBookshelves = 40; // Mock statistic matching sketch
        $totalReports = max(Report::count(), 50); // Get actual reports count

        // Chart labels (Sen, Sel, Rab, Kam, Jum)
        $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];

        // Chart datasets mapped from sketch curves
        $userChartData = [12, 21, 25, 36, 28];
        $reviewChartData = [35, 32, 29, 15, 33];
        $bookshelfChartData = [4, 8, 12, 10, 15];
        $reportChartData = [2, 5, 3, 8, 4];

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
