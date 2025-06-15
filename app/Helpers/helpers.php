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