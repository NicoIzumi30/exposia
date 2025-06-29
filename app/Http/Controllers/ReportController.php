<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }
    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        return view('report.create');
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'website_url' => ['required', 'url'],
            'report_type' => ['required', 'string', 'in:inappropriate,spam,offensive,copyright,illegal,other'],
            'report_content' => ['required', 'string', 'min:10'],
            'evidence_image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'terms' => ['required', 'accepted'],
        ]);
        
        // Generate unique report code
        $reportCode = $this->generateReportCode();
        
        // Handle image upload if provided
        $evidenceImagePath = null;
        if ($request->hasFile('evidence_image')) {
            $evidenceImagePath = $request->file('evidence_image')
                ->store('report_evidence', 'public');
        }
        
        // Try to find the business by URL
        $businessId = null;
        $websiteUrl = $validated['website_url'];
        
        // Extract domain or path from URL
        $urlParts = parse_url($websiteUrl);
        $domain = $urlParts['host'] ?? '';
        $path = $urlParts['path'] ?? '';
        
        // Try to match with business public_url
        $business = Business::where('public_url', 'like', "%{$domain}%")
                           ->orWhere('public_url', 'like', "%{$path}%")
                           ->first();
        
        if ($business) {
            $businessId = $business->id;
        }
        
        // Create the report
        $report = Report::create([
            'user_id' => auth()->id(), // Will be null for guest users
            'business_id' => $businessId,
            'report_code' => $reportCode,
            'report_type' => $validated['report_type'],
            'report_content' => $validated['report_content'],
            'website_url' => $validated['website_url'],
            'evidence_image' => $evidenceImagePath,
            'status' => 'pending',
        ]);
        
        // Redirect to confirmation page
        return redirect()
            ->route('report.confirmation', $report)
            ->with('success', 'Laporan berhasil dikirim.');
    }
    
    /**
     * Display a confirmation page after a report is submitted.
     */
    public function confirmation(Report $report)
    {
        return view('report.confirmation', compact('report'));
    }
    
    /**
     * Show the form for checking a report status.
     */
    public function check()
    {
        return view('report.check');
    }
    
    /**
     * Check the status of a report.
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'report_code' => ['required', 'string'],
        ]);
        
        $report = Report::where('report_code', $validated['report_code'])->first();
        
        if (!$report) {
            return redirect()
                ->route('report.check')
                ->with('report_not_found', 'Kode laporan tidak ditemukan. Mohon periksa kembali kode yang Anda masukkan.');
        }
        
        return view('report.check', compact('report'));
    }
    
    /**
     * Generate unique report code
     */
    private function generateReportCode()
    {
        do {
            // Format: RPT-XXXXX where X is alphanumeric
            $code = 'RPT-' . strtoupper(Str::random(5));
        } while (Report::where('report_code', $code)->exists());
        
        return $code;
    }
}