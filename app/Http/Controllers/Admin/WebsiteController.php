<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\BusinessTemplate;
use App\Models\BusinessVisitor;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    /**
     * Display a listing of websites.
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering
        $search = $request->input('search');
        $status = $request->input('status');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Start query builder
        $websitesQuery = Business::with('user');
        
        // Apply search filter if provided
        if ($search) {
            $websitesQuery->where(function($query) use ($search) {
                $query->where('business_name', 'like', "%{$search}%")
                      ->orWhere('public_url', 'like', "%{$search}%")
                      ->orWhere('main_address', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter if provided
        if ($status) {
            if ($status == 'published') {
                $websitesQuery->where('publish_status', true);
            } elseif ($status == 'draft') {
                $websitesQuery->where('publish_status', false);
            }
        }
        
        // Apply sorting
        $websitesQuery->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $websites = $websitesQuery->paginate(10)
                                ->withQueryString();
        
        // Get counts for filters
        $counts = [
            'all' => Business::count(),
            'published' => Business::where('publish_status', true)->count(),
            'draft' => Business::where('publish_status', false)->count(),
        ];
        
        return view('admin.websites.index', compact('websites', 'counts', 'search', 'status', 'sortField', 'sortDirection'));
    }

    /**
     * Display the specified website.
     */
    public function show(Business $website)
    {
        // Get visitor statistics
        $visitorStats = BusinessVisitor::where('business_id', $website->id)
                                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as visitors'))
                                    ->groupBy('date')
                                    ->orderBy('date', 'desc')
                                    ->limit(7)
                                    ->get();
        
        // Get related data
        $branches = $website->branches()->count();
        $products = $website->products()->count();
        $galleries = $website->galleries()->count();
        $testimonials = $website->testimonials()->count();
        
        return view('admin.websites.show', compact('website', 'visitorStats', 'branches', 'products', 'galleries', 'testimonials'));
    }

    /**
     * Show the form for editing the specified website.
     */
    public function edit(Business $website)
    {
        return view('admin.websites.edit', compact('website'));
    }

    /**
     * Update the specified website.
     */
    public function update(Request $request, Business $website)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'main_address' => ['nullable', 'string', 'max:255'],
            'main_operational_hours' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Log old values
        $oldValues = $website->only(['business_name', 'short_description', 'main_address', 'main_operational_hours']);
        
        $website->update($validated);
        
        activity()
            ->performedOn($website)
            ->withProperties([
                'action' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $website->only(['business_name', 'short_description', 'main_address', 'main_operational_hours'])
            ])
            ->log('Website updated by admin');
        
        return redirect()
            ->route('admin.websites.show', $website)
            ->with('success', "Website {$website->business_name} berhasil diperbarui");
    }

    /**
     * Preview the website.
     */
    public function preview(Business $website)
    {
        // Redirect to the public website URL
        return redirect()->to(url($website->public_url));
    }

    /**
     * Publish the website.
     */
    public function publish(Business $website)
    {
        if ($website->publish_status) {
            return redirect()
                ->route('admin.websites.show', $website)
                ->with('warning', "Website {$website->business_name} sudah dipublikasikan");
        }
        
        $website->publish_status = true;
        $website->save();
        
        activity()
            ->performedOn($website)
            ->withProperties(['action' => 'published'])
            ->log('Website published by admin');
        
        return redirect()
            ->route('admin.websites.show', $website)
            ->with('success', "Website {$website->business_name} berhasil dipublikasikan");
    }

    /**
     * Unpublish the website.
     */
    public function unpublish(Business $website)
    {
        if (!$website->publish_status) {
            return redirect()
                ->route('admin.websites.show', $website)
                ->with('warning', "Website {$website->business_name} sudah berstatus draft");
        }
        
        $website->publish_status = false;
        $website->save();
        
        activity()
            ->performedOn($website)
            ->withProperties(['action' => 'unpublished'])
            ->log('Website unpublished by admin');
        
        return redirect()
            ->route('admin.websites.show', $website)
            ->with('success', "Website {$website->business_name} berhasil dinonaktifkan");
    }
}