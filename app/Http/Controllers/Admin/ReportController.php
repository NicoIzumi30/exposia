<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering
        $search = $request->input('search');
        $status = $request->input('status', 'pending');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Start query builder
        $reportsQuery = Report::with(['user', 'business']);
        
        // Apply search filter if provided
        if ($search) {
            $reportsQuery->where(function($query) use ($search) {
                $query->where('report_code', 'like', "%{$search}%")
                      ->orWhere('report_content', 'like', "%{$search}%")
                      ->orWhere('website_url', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('business', function($q) use ($search) {
                          $q->where('business_name', 'like', "%{$search}%");
                      });
            });
        }
        
        // Apply status filter
        if ($status !== 'all') {
            $reportsQuery->where('status', $status);
        }
        
        // Apply type filter if provided
        if ($type) {
            $reportsQuery->where('report_type', $type);
        }
        
        // Apply date range filter if provided
        if ($startDate) {
            $reportsQuery->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $reportsQuery->whereDate('created_at', '<=', $endDate);
        }
        
        // Order by latest
        $reportsQuery->latest();
        
        // Get paginated results
        $reports = $reportsQuery->paginate(10)
                              ->withQueryString();
        
        // Get counts for filters
        $counts = [
            'all' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];
        
        // Get report types for filter
        $reportTypes = Report::select('report_type')
                           ->distinct()
                           ->pluck('report_type');
        
        return view('admin.reports.index', compact(
            'reports', 
            'counts', 
            'reportTypes',
            'search', 
            'status', 
            'type',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load(['user', 'business']);
        
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Show form to resolve a report.
     */
    public function showResolveForm(Report $report)
    {
        if ($report->status !== 'pending') {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('warning', 'Laporan ini sudah ditindaklanjuti');
        }
        
        $report->load(['user', 'business']);
        
        return view('admin.reports.resolve', compact('report'));
    }

    /**
     * Resolve the specified report.
     */
    public function resolve(Request $request, Report $report)
    {
        if ($report->status !== 'pending') {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('warning', 'Laporan ini sudah ditindaklanjuti');
        }
        
        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'min:10'],
            'action_taken' => ['required', 'string'],
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update report status
            $report->status = 'resolved';
            $report->admin_notes = $validated['admin_notes'];
            $report->resolved_at = now();
            $report->save();
            
            // Take action based on the selected action
            if ($validated['action_taken'] === 'suspend_user' && $report->user_id) {
                $user = User::find($report->user_id);
                if ($user) {
                    $user->suspend('Report resolution: ' . $validated['admin_notes']);
                }
            } elseif ($validated['action_taken'] === 'unpublish_business' && $report->business_id) {
                $business = Business::find($report->business_id);
                if ($business) {
                    $business->update(['publish_status' => 'draft']);
                }
            }
            
            // Log activity
            activity()
                ->performedOn($report)
                ->withProperties([
                    'action' => 'resolved',
                    'admin_notes' => $validated['admin_notes'],
                    'action_taken' => $validated['action_taken']
                ])
                ->log('Report resolved by admin');
            
            DB::commit();
            
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('success', 'Laporan berhasil diselesaikan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Reject the specified report.
     */
    public function reject(Request $request, Report $report)
    {
        if ($report->status !== 'pending') {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('warning', 'Laporan ini sudah ditindaklanjuti');
        }
        
        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'min:10'],
        ]);
        
        $report->status = 'rejected';
        $report->admin_notes = $validated['admin_notes'];
        $report->resolved_at = now();
        $report->save();
        
        activity()
            ->performedOn($report)
            ->withProperties([
                'action' => 'rejected',
                'admin_notes' => $validated['admin_notes']
            ])
            ->log('Report rejected by admin');
        
        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Laporan telah ditolak');
    }
}