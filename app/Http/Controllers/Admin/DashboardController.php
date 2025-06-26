<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessVisitor;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get overall platform statistics
        $stats = $this->getPlatformStats();
        
        // Get recent activities (platform-wide)
        $recentActivities = $this->getRecentActivities();
        
        // Get new users and businesses
        $newUsers = $this->getNewUsers();
        $newWebsites = $this->getNewWebsites();
        
        return view('admin.dashboard.index', compact(
            'stats',
            'recentActivities',
            'newUsers',
            'newWebsites'
        ));
    }

    /**
     * Get platform-wide statistics
     */
    private function getPlatformStats()
    {
        // Total users count
        $totalUsers = User::where('role', 'user')->count();
        
        // Total active sites
        $totalActiveSites = Business::where('publish_status', 'published')->count();
        
        // Total sites
        $totalSites = Business::count();
        
        // Total visitors
        $totalVisitors = BusinessVisitor::count();
        
        // Visitors today
        $visitorsToday = BusinessVisitor::whereDate('created_at', Carbon::today())->count();
        
        // Visitors yesterday
        $visitorsYesterday = BusinessVisitor::whereDate('created_at', Carbon::yesterday())->count();
        
        // Calculate growth percentage
        $growthPercentage = $visitorsYesterday > 0 
            ? round((($visitorsToday - $visitorsYesterday) / $visitorsYesterday) * 100, 1)
            : ($visitorsToday > 0 ? 100 : 0);
        
        // Get visitors for last 7 days
        $last7Days = Carbon::now()->subDays(7);
        $visitorsLast7Days = BusinessVisitor::where('created_at', '>=', $last7Days)->count();
        
        // Calculate average completion rate across all businesses
        $avgCompletionRate = Business::avg('progress_completion') ?? 0;
        
        return [
            'total_users' => $totalUsers,
            'total_sites' => $totalSites,
            'total_active_sites' => $totalActiveSites,
            'total_visitors' => $totalVisitors,
            'visitors_today' => $visitorsToday,
            'visitors_yesterday' => $visitorsYesterday,
            'visitors_last_7_days' => $visitorsLast7Days,
            'growth_percentage' => $growthPercentage,
            'avg_completion_rate' => round($avgCompletionRate, 1)
        ];
    }

    /**
     * Get recent platform activities
     */
    private function getRecentActivities()
    {
        // In a real implementation, you'd use the ActivityLog model here
        $activities = collect();
        
        // Get recent user registrations
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'action' => 'registered',
                    'title' => "User baru: {$user->name}",
                    'time' => $user->created_at,
                    'icon' => 'fa-user-plus',
                    'color' => 'text-green-500'
                ];
            });
            
        // Get recently published websites
        $recentPublished = Business::where('publish_status', 'published')
            ->latest('updated_at')
            ->limit(3)
            ->get()
            ->map(function ($business) {
                return [
                    'type' => 'website',
                    'action' => 'published',
                    'title' => "Website baru: {$business->business_name}",
                    'time' => $business->updated_at,
                    'icon' => 'fa-globe',
                    'color' => 'text-blue-500'
                ];
            });
            
        return $activities
            ->merge($recentUsers)
            ->merge($recentPublished)
            ->sortByDesc('time')
            ->take(10);
    }
    
    /**
     * Get newly registered users
     */
    private function getNewUsers()
    {
        return User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();
    }
    
    /**
     * Get newly created websites
     */
    private function getNewWebsites()
    {
        return Business::latest()
            ->limit(5)
            ->get();
    }
}