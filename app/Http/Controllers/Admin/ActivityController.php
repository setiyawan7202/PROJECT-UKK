<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display activity log with filters
     */
    public function index(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activities = $query->paginate(20)->withQueryString();

        // Get unique action types for filter dropdown
        $actionTypes = Activity::select('action')->distinct()->pluck('action');

        // Get users for filter dropdown
        $users = \App\Models\Auth::orderBy('username')->get();

        return view('admin.activity.index', compact('activities', 'actionTypes', 'users'));
    }

    /**
     * Export activity log to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activities = $query->limit(500)->get(); // Limit for PDF performance

        $pdf = Pdf::loadView('pdf.activity-log', [
            'activities' => $activities,
            'filters' => [
                'action' => $request->action,
                'user_id' => $request->user_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ],
            'generatedAt' => Carbon::now()->format('d M Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = 'activity_log_' . Carbon::now()->format('Y-m-d_His') . '.pdf';

        \App\Helpers\ActivityLogger::log('Export', 'Mengexport Activity Log ke PDF');

        return $pdf->download($filename);
    }
}
