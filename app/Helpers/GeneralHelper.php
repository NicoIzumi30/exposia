<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GeneralHelper
{
    /**
     * Generate unique business URL
     */
    public static function generateBusinessUrl(string $businessName): string
    {
        $baseUrl = Str::slug($businessName);
        $url = $baseUrl;
        $counter = 1;

        while (\App\Models\Business::where('public_url', $url)->exists()) {
            $url = $baseUrl . '-' . $counter;
            $counter++;
        }

        return $url;
    }

    /**
     * Format phone number to WhatsApp format
     */
    public static function formatPhoneForWhatsApp(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // Add +62 if starts with 0
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Add + prefix if not present
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }

    /**
     * Get WhatsApp link
     */
    public static function getWhatsAppLink(string $phone, string $message = ''): string
    {
        $formattedPhone = str_replace('+', '', self::formatPhoneForWhatsApp($phone));
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedPhone}" . ($message ? "?text={$encodedMessage}" : '');
    }

    /**
     * Generate user initials
     */
    public static function getUserInitials(string $name): string
    {
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Calculate business completion percentage
     */
    public static function calculateBusinessCompletion($business): int
    {
        if (!$business) {
            return 0;
        }

        $completionSteps = [
            'business_name' => $business->business_name ? 15 : 0,
            'main_address' => $business->main_address ? 10 : 0,
            'short_description' => $business->short_description ? 10 : 0,
            'logo_url' => $business->logo_url ? 10 : 0,
            'products' => $business->products()->count() >= 1 ? 15 : 0,
            'galleries' => $business->galleries()->count() >= 3 ? 10 : 0,
            'testimonials' => $business->testimonials()->count() >= 1 ? 10 : 0,
            'template' => $business->businessTemplate ? 10 : 0,
            'published' => $business->publish_status === 'published' ? 10 : 0,
        ];

        return array_sum($completionSteps);
    }

    /**
     * Upload file with validation
     */
    public static function uploadFile($file, string $path = 'uploads', array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        
        if (!in_array(strtolower($extension), $allowedTypes)) {
            return null;
        }

        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        
        try {
            $file->storeAs($path, $filename, 'public');
            return $path . '/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete file from storage
     */
    public static function deleteFile(?string $filePath): bool
    {
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return false;
        }

        try {
            return Storage::disk('public')->delete($filePath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format file size
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    /**
     * Generate QR code data URL
     */
    public static function generateQRCode(string $data): string
    {
        // This would integrate with a QR code library
        // For now, return a placeholder
        return "data:image/svg+xml;base64," . base64_encode("
            <svg width='200' height='200' xmlns='http://www.w3.org/2000/svg'>
                <rect width='200' height='200' fill='white'/>
                <text x='100' y='100' text-anchor='middle' fill='black'>QR Code</text>
            </svg>
        ");
    }

    /**
     * Truncate text with ellipsis
     */
    public static function truncateText(string $text, int $limit = 100, string $end = '...'): string
    {
        return Str::limit($text, $limit, $end);
    }

    /**
     * Generate random password
     */
    public static function generateRandomPassword(int $length = 12): string
    {
        return Str::random($length);
    }

    /**
     * Check if URL is valid
     */
    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Convert array to CSV string
     */
    public static function arrayToCsv(array $data): string
    {
        $output = fopen('php://memory', 'w');
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    /**
     * Get avatar URL (placeholder for Gravatar or similar)
     */
    public static function getAvatarUrl(string $email, int $size = 80): string
    {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    }

    /**
     * Format date for display
     */
    public static function formatDate($date, string $format = 'd M Y'): string
    {
        if (!$date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Format datetime for display
     */
    public static function formatDateTime($datetime, string $format = 'd M Y H:i'): string
    {
        if (!$datetime) {
            return '-';
        }

        return \Carbon\Carbon::parse($datetime)->format($format);
    }

    /**
     * Get time ago format
     */
    public static function timeAgo($datetime): string
    {
        if (!$datetime) {
            return '-';
        }

        return \Carbon\Carbon::parse($datetime)->diffForHumans();
    }
}