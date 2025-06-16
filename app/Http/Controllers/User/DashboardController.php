<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $business = $user->business;
        
        // Dashboard statistics
        $stats = $this->getDashboardStats($business);
        
        // Recent activities (last 7 days)
        $recentActivities = $this->getRecentActivities($business);
        
        // Website status
        $websiteStatus = $this->getWebsiteStatus($business);
        
        return view('user.dashboard.index', compact(
            'user',
            'business', 
            'stats',
            'recentActivities',
            'websiteStatus'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($business)
    {
        if (!$business) {
            return [
                'progress_completion' => 0,
                'visitors_last_7_days' => 0,
                'visitors_total' => 0,
                'products_count' => 0,
                'galleries_count' => 0,
                'testimonials_count' => 0,
                'visitors_today' => 0,
                'visitors_yesterday' => 0,
                'growth_percentage' => 0
            ];
        }

        // Get visitor stats
        $last7Days = Carbon::now()->subDays(7);
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();

        $visitorsLast7Days = $business->visitors()
            ->where('created_at', '>=', $last7Days)
            ->count();

        $visitorsToday = $business->visitors()
            ->whereDate('created_at', $today)
            ->count();

        $visitorsYesterday = $business->visitors()
            ->whereDate('created_at', $yesterday)
            ->count();

        $visitorsTotal = $business->visitors()->count();

        // Calculate growth percentage
        $growthPercentage = $visitorsYesterday > 0 
            ? round((($visitorsToday - $visitorsYesterday) / $visitorsYesterday) * 100, 1)
            : ($visitorsToday > 0 ? 100 : 0);

        // Count related content
        $productsCount = $business->products()->count();
        $galleriesCount = $business->galleries()->count();
        $testimonialsCount = $business->testimonials()->count();

        // Calculate progress completion
        $progressCompletion = $this->calculateProgressCompletion($business);

        return [
            'progress_completion' => $progressCompletion,
            'visitors_last_7_days' => $visitorsLast7Days,
            'visitors_total' => $visitorsTotal,
            'products_count' => $productsCount,
            'galleries_count' => $galleriesCount,
            'testimonials_count' => $testimonialsCount,
            'visitors_today' => $visitorsToday,
            'visitors_yesterday' => $visitorsYesterday,
            'growth_percentage' => $growthPercentage
        ];
    }

    /**
     * Calculate business profile completion percentage
     */
    private function calculateProgressCompletion($business)
    {
        if (!$business) return 0;

        $fields = [
            'business_name' => !empty($business->business_name),
            'main_address' => !empty($business->main_address),
            'main_operational_hours' => !empty($business->main_operational_hours),
            'logo_url' => !empty($business->logo_url),
            'short_description' => !empty($business->short_description),
            'full_description' => !empty($business->full_description),
            'products' => $business->products()->count() > 0,
            'galleries' => $business->galleries()->count() > 0
        ];

        $completedFields = array_filter($fields);
        $totalFields = count($fields);
        
        return $totalFields > 0 ? round((count($completedFields) / $totalFields) * 100) : 0;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($business)
    {
        if (!$business) return collect();

        // Get recent activity logs related to this business
        $activities = collect();

        // Recent products
        $recentProducts = $business->products()
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($product) {
                return [
                    'type' => 'product',
                    'action' => 'added',
                    'title' => "Produk '{$product->product_name}' ditambahkan",
                    'time' => $product->created_at,
                    'icon' => 'fa-box',
                    'color' => 'text-blue-500'
                ];
            });

        // Recent galleries
        $recentGalleries = $business->galleries()
            ->latest()
            ->limit(2)
            ->get()
            ->map(function ($gallery) {
                return [
                    'type' => 'gallery',
                    'action' => 'added',
                    'title' => 'Gambar galeri baru ditambahkan',
                    'time' => $gallery->created_at,
                    'icon' => 'fa-image',
                    'color' => 'text-purple-500'
                ];
            });

        // Recent testimonials
        $recentTestimonials = $business->testimonials()
            ->latest()
            ->limit(2)
            ->get()
            ->map(function ($testimonial) {
                return [
                    'type' => 'testimonial',
                    'action' => 'added',
                    'title' => "Testimoni dari {$testimonial->testimonial_name}",
                    'time' => $testimonial->created_at,
                    'icon' => 'fa-quote-right',
                    'color' => 'text-green-500'
                ];
            });

        return $activities
            ->merge($recentProducts)
            ->merge($recentGalleries)
            ->merge($recentTestimonials)
            ->sortByDesc('time')
            ->take(5);
    }

    /**
     * Get website status information
     */
    private function getWebsiteStatus($business)
    {
        if (!$business) {
            return [
                'is_published' => false,
                'public_url' => null,
                'qr_code' => null,
                'last_updated' => null,
                'status_text' => 'Draft',
                'status_color' => 'gray'
            ];
        }

        $isPublished = $business->publish_status === 'published';
        $statusColor = $isPublished ? 'green' : ($business->publish_status === 'draft' ? 'yellow' : 'gray');
        $statusText = $isPublished ? 'Published' : ucfirst($business->publish_status ?? 'Draft');

        return [
            'is_published' => $isPublished,
            'public_url' => $business->public_url,
            'qr_code' => $business->qr_code,
            'last_updated' => $business->updated_at,
            'status_text' => $statusText,
            'status_color' => $statusColor
        ];
    }

    /**
     * Business data management page
     */
    public function business()
    {
        return view('user.dashboard.business');
    }

    /**
     * Branches management page
     */
    public function branches()
    {
        return view('user.dashboard.branches');
    }

    /**
     * Products management page
     */
    public function products()
    {
        return view('user.dashboard.products');
    }

    /**
     * Gallery management page
     */
    public function gallery()
    {
        return view('user.dashboard.gallery');
    }

    /**
     * Testimonials management page
     */
    public function testimonials()
    {
        return view('user.dashboard.testimonials');
    }

    /**
     * About business page
     */
    public function about()
    {
        return view('user.dashboard.about');
    }

    /**
     * Templates and appearance page
     */
    public function templates()
    {
        return view('user.dashboard.templates');
    }

    /**
     * AI content generator page
     */
    public function aiContent()
    {
        return view('user.dashboard.ai-content');
    }

    /**
     * Publish and website link page
     */
    public function publish()
    {
        return view('user.dashboard.publish');
    }

    /**
     * Help and support page
     */
    public function support()
    {
        return view('user.dashboard.support');
    }

    /**
     * Account management page
     */
    public function account()
    {
        return view('user.dashboard.account');
    }
}