<?php

/**
 * Main Helper File - Global Functions
 * 
 * This file contains global helper functions that are available
 * throughout the application. It serves as a bridge to the
 * organized helper classes.
 */

use App\Helpers\ActivityHelper;
use App\Helpers\GeneralHelper;
use App\Helpers\BranchHelper;

// =============================================================================
// ACTIVITY LOGGING HELPERS
// =============================================================================

if (!function_exists('activity')) {
    /**
     * Log activity using builder pattern
     * 
     * @param string|null $description
     * @return \App\Helpers\ActivityBuilder
     */
    function activity($description = null)
    {
        return ActivityHelper::log($description);
    }
}

if (!function_exists('log_activity')) {
    /**
     * Simple activity logging
     * 
     * @param string $action
     * @param mixed $model
     * @param array $properties
     * @return \App\Models\ActivityLog
     */
    function log_activity(string $action, $model = null, array $properties = [])
    {
        return ActivityHelper::create($action, $model, $properties);
    }
}

if (!function_exists('log_activity_for')) {
    /**
     * Log activity for specific user
     * 
     * @param mixed $user
     * @param string $action
     * @param mixed $model
     * @param array $properties
     * @return \App\Models\ActivityLog
     */
    function log_activity_for($user, string $action, $model = null, array $properties = [])
    {
        return ActivityHelper::logFor($user, $action, $model, $properties);
    }
}

// =============================================================================
// GENERAL UTILITY HELPERS
// =============================================================================

if (!function_exists('generate_business_url')) {
    /**
     * Generate unique business URL
     * 
     * @param string $businessName
     * @return string
     */
    function generate_business_url(string $businessName): string
    {
        return GeneralHelper::generateBusinessUrl($businessName);
    }
}

if (!function_exists('format_phone_wa')) {
    /**
     * Format phone number for WhatsApp
     * 
     * @param string $phone
     * @return string
     */
    function format_phone_wa(string $phone): string
    {
        return GeneralHelper::formatPhoneForWhatsApp($phone);
    }
}

if (!function_exists('whatsapp_link')) {
    /**
     * Get WhatsApp link
     * 
     * @param string $phone
     * @param string $message
     * @return string
     */
    function whatsapp_link(string $phone, string $message = ''): string
    {
        return GeneralHelper::getWhatsAppLink($phone, $message);
    }
}

if (!function_exists('user_initials')) {
    /**
     * Get user initials
     * 
     * @param string $name
     * @return string
     */
    function user_initials(string $name): string
    {
        return GeneralHelper::getUserInitials($name);
    }
}

if (!function_exists('upload_file')) {
    /**
     * Upload file with validation
     * 
     * @param mixed $file
     * @param string $path
     * @param array $allowedTypes
     * @return string|null
     */
    function upload_file($file, string $path = 'uploads', array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']): ?string
    {
        return GeneralHelper::uploadFile($file, $path, $allowedTypes);
    }
}

if (!function_exists('delete_file')) {
    /**
     * Delete file from storage
     * 
     * @param string|null $filePath
     * @return bool
     */
    function delete_file(?string $filePath): bool
    {
        return GeneralHelper::deleteFile($filePath);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size
     * 
     * @param int $bytes
     * @return string
     */
    function format_file_size(int $bytes): string
    {
        return GeneralHelper::formatFileSize($bytes);
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text with ellipsis
     * 
     * @param string $text
     * @param int $limit
     * @param string $end
     * @return string
     */
    function truncate_text(string $text, int $limit = 100, string $end = '...'): string
    {
        return GeneralHelper::truncateText($text, $limit, $end);
    }
}

if (!function_exists('avatar_url')) {
    /**
     * Get avatar URL
     * 
     * @param string $email
     * @param int $size
     * @return string
     */
    function avatar_url(string $email, int $size = 80): string
    {
        return GeneralHelper::getAvatarUrl($email, $size);
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date for display
     * 
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function format_date($date, string $format = 'd M Y'): string
    {
        return GeneralHelper::formatDate($date, $format);
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format datetime for display
     * 
     * @param mixed $datetime
     * @param string $format
     * @return string
     */
    function format_datetime($datetime, string $format = 'd M Y H:i'): string
    {
        return GeneralHelper::formatDateTime($datetime, $format);
    }
}

if (!function_exists('time_ago')) {
    /**
     * Get time ago format
     * 
     * @param mixed $datetime
     * @return string
     */
    function time_ago($datetime): string
    {
        return GeneralHelper::timeAgo($datetime);
    }
}

if (!function_exists('business_completion')) {
    /**
     * Calculate business completion percentage
     * 
     * @param mixed $business
     * @return int
     */
    function business_completion($business): int
    {
        return GeneralHelper::calculateBusinessCompletion($business);
    }
}

// =============================================================================
// APP SPECIFIC HELPERS
// =============================================================================

if (!function_exists('app_name')) {
    /**
     * Get application name
     * 
     * @return string
     */
    function app_name(): string
    {
        return config('app.name', 'UMKM Platform');
    }
}

if (!function_exists('app_version')) {
    /**
     * Get application version
     * 
     * @return string
     */
    function app_version(): string
    {
        return config('app.version', '1.0.0');
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     * 
     * @return bool
     */
    function is_admin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}

if (!function_exists('current_user')) {
    /**
     * Get current authenticated user
     * 
     * @return \App\Models\User|null
     */
    function current_user()
    {
        return auth()->user();
    }
}

if (!function_exists('user_business')) {
    /**
     * Get current user's business
     * 
     * @return \App\Models\Business|null
     */
    function user_business()
    {
        return auth()->check() ? auth()->user()->business : null;
    }
}


// Updated helper functions to work with boolean publish_status

if (!function_exists('business_completion')) {
    /**
     * Calculate business profile completion percentage
     * 
     * @param \App\Models\Business $business
     * @return int
     */
    function business_completion($business)
    {
        if (!$business) {
            return 0;
        }

        $fields = [
            'business_name' => !empty($business->business_name),
            'main_address' => !empty($business->main_address),
            'main_operational_hours' => !empty($business->main_operational_hours),
            'logo_url' => !empty($business->logo_url),
            'short_description' => !empty($business->short_description),
            'full_description' => !empty($business->full_description),
            'google_maps_link' => !empty($business->google_maps_link),
            'has_products' => $business->products()->count() > 0,
            'has_galleries' => $business->galleries()->count() > 0,
            'has_testimonials' => $business->testimonials()->count() > 0,
        ];

        $completedFields = array_filter($fields);
        $totalFields = count($fields);
        
        return $totalFields > 0 ? round((count($completedFields) / $totalFields) * 100) : 0;
    }
}

if (!function_exists('generate_business_url')) {
    /**
     * Generate unique business URL slug
     * 
     * @param string $businessName
     * @param int|null $excludeId
     * @return string
     */
    function generate_business_url($businessName, $excludeId = null)
    {
        // Convert to slug
        $slug = \Illuminate\Support\Str::slug($businessName, '-');
        
        // Ensure it's not too long
        $slug = substr($slug, 0, 50);
        
        // Check if slug already exists
        $originalSlug = $slug;
        $counter = 1;
        
        while (business_url_exists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}

if (!function_exists('business_url_exists')) {
    /**
     * Check if business URL already exists
     * 
     * @param string $url
     * @param int|null $excludeId
     * @return bool
     */
    function business_url_exists($url, $excludeId = null)
    {
        $query = \App\Models\Business::where('public_url', 'like', "%/{$url}");
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}

if (!function_exists('format_business_address')) {
    /**
     * Format business address for display
     * 
     * @param string $address
     * @param int $maxLength
     * @return string
     */
    function format_business_address($address, $maxLength = 100)
    {
        if (strlen($address) <= $maxLength) {
            return $address;
        }
        
        return substr($address, 0, $maxLength) . '...';
    }
}

if (!function_exists('business_status_badge')) {
    /**
     * Get business status badge HTML (Updated for boolean)
     * 
     * @param \App\Models\Business $business
     * @return string
     */
    function business_status_badge($business)
    {
        if (!$business) {
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">Not Set</span>';
        }
        
        // Handle boolean publish_status
        if ($business->publish_status === true || $business->publish_status === 1) {
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">Published</span>';
        } else {
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">Draft</span>';
        }
    }
}

if (!function_exists('business_logo_url')) {
    /**
     * Get business logo URL with fallback
     * 
     * @param \App\Models\Business $business
     * @param string $default
     * @return string
     */
    function business_logo_url($business, $default = null)
    {
        if (!$business || !$business->logo_url) {
            return $default ?: asset('images/default-business-logo.png');
        }
        
        // If logo_url is a full URL, return as is
        if (filter_var($business->logo_url, FILTER_VALIDATE_URL)) {
            return $business->logo_url;
        }
        
        // Otherwise, assume it's a storage path
        return \Illuminate\Support\Facades\Storage::url($business->logo_url);
    }
}

if (!function_exists('generate_qr_code_url')) {
    /**
     * Generate QR code URL for business
     * 
     * @param string $businessUrl
     * @param int $size
     * @return string
     */
    function generate_qr_code_url($businessUrl, $size = 200)
    {
        $baseUrl = "https://api.qrserver.com/v1/create-qr-code/";
        $params = http_build_query([
            'size' => $size . 'x' . $size,
            'data' => $businessUrl,
            'format' => 'png',
            'bgcolor' => 'ffffff',
            'color' => '000000',
            'qzone' => 2
        ]);
        
        return $baseUrl . '?' . $params;
    }
}

if (!function_exists('business_operational_hours_array')) {
    /**
     * Convert operational hours string to array
     * 
     * @param string $hours
     * @return array
     */
    function business_operational_hours_array($hours)
    {
        // Simple parsing - can be enhanced based on your needs
        if (empty($hours)) {
            return [];
        }
        
        // Example: "Senin-Sabtu 08:00-17:00" -> ['Senin-Sabtu', '08:00-17:00']
        $parts = explode(' ', trim($hours), 2);
        
        return [
            'days' => $parts[0] ?? '',
            'time' => $parts[1] ?? ''
        ];
    }
}

if (!function_exists('validate_google_maps_url')) {
    /**
     * Validate Google Maps URL
     * 
     * @param string $url
     * @return bool
     */
    function validate_google_maps_url($url)
    {
        if (empty($url)) {
            return true; // Empty is allowed
        }
        
        // Check if it's a valid URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Check if it's a Google Maps URL
        $validDomains = [
            'maps.google.com',
            'www.google.com/maps',
            'google.com/maps',
            'goo.gl/maps'
        ];
        
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';
        
        foreach ($validDomains as $domain) {
            if (strpos($host, $domain) !== false) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('get_business_status_text')) {
    /**
     * Get business status as text (for boolean)
     * 
     * @param \App\Models\Business $business
     * @return string
     */
    function get_business_status_text($business)
    {
        if (!$business) {
            return 'Not Set';
        }
        
        return ($business->publish_status === true || $business->publish_status === 1) ? 'Published' : 'Draft';
    }
}

if (!function_exists('get_business_status_color')) {
    /**
     * Get business status color class (for boolean)
     * 
     * @param \App\Models\Business $business
     * @return string
     */
    function get_business_status_color($business)
    {
        if (!$business) {
            return 'gray';
        }
        
        return ($business->publish_status === true || $business->publish_status === 1) ? 'green' : 'yellow';
    }
}