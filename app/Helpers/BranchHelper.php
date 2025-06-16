<?php

if (!function_exists('format_phone_wa')) {
    /**
     * Format phone number for WhatsApp
     * Converts various phone formats to WhatsApp-compatible format
     */
    function format_phone_wa($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d\+]/', '', $phone);

        // If phone starts with 0, replace with +62 (Indonesia)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        }
        
        // If phone doesn't start with +, add +62
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+62' . $phone;
        }

        // Remove duplicate +62 if exists
        $phone = preg_replace('/^\+62\+62/', '+62', $phone);

        return $phone;
    }
}

if (!function_exists('whatsapp_link')) {
    /**
     * Generate WhatsApp chat link
     */
    function whatsapp_link($phone, $message = '')
    {
        $formattedPhone = format_phone_wa($phone);
        
        if (!$formattedPhone) {
            return '#';
        }

        // Remove + from phone number for WhatsApp API
        $phoneNumber = str_replace('+', '', $formattedPhone);
        
        $baseUrl = 'https://wa.me/' . $phoneNumber;
        
        if (!empty($message)) {
            $baseUrl .= '?text=' . urlencode($message);
        }

        return $baseUrl;
    }
}

if (!function_exists('validate_google_maps_url')) {
    /**
     * Validate if URL is a valid Google Maps URL
     */
    function validate_google_maps_url($url)
    {
        if (empty($url)) {
            return true; // Allow empty URLs
        }

        // Check if it's a valid URL first
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Valid Google Maps URL patterns
        $patterns = [
            '/^https:\/\/(www\.)?google\.com\/maps/',
            '/^https:\/\/maps\.google\.com/',
            '/^https:\/\/goo\.gl\/maps/',
            '/^https:\/\/maps\.app\.goo\.gl/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('get_branch_stats')) {
    /**
     * Get branch statistics for a business
     */
    function get_branch_stats($business)
    {
        if (!$business) {
            return [
                'total' => 0,
                'with_phone' => 0,
                'with_maps' => 0,
                'completion_rate' => 0
            ];
        }

        $branches = $business->branches;
        $total = $branches->count();
        $withPhone = $branches->whereNotNull('branch_phone')->count();
        $withMaps = $branches->whereNotNull('branch_google_maps_link')->count();
        
        // Calculate completion rate based on how many branches have all optional fields
        $completeBranches = $branches->filter(function ($branch) {
            return !empty($branch->branch_phone) && !empty($branch->branch_google_maps_link);
        })->count();
        
        $completionRate = $total > 0 ? round(($completeBranches / $total) * 100) : 0;

        return [
            'total' => $total,
            'with_phone' => $withPhone,
            'with_maps' => $withMaps,
            'completion_rate' => $completionRate
        ];
    }
}

if (!function_exists('format_branch_address')) {
    /**
     * Format branch address for display
     */
    function format_branch_address($address, $maxLength = 100)
    {
        if (empty($address)) {
            return '-';
        }

        if (strlen($address) <= $maxLength) {
            return $address;
        }

        return substr($address, 0, $maxLength) . '...';
    }
}

if (!function_exists('get_branch_distance')) {
    /**
     * Calculate distance between two coordinates (in kilometers)
     * Using Haversine formula
     */
    function get_branch_distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}

if (!function_exists('extract_coordinates_from_maps_url')) {
    /**
     * Extract latitude and longitude from Google Maps URL
     */
    function extract_coordinates_from_maps_url($mapsUrl)
    {
        if (empty($mapsUrl)) {
            return null;
        }

        // Pattern for extracting coordinates from various Google Maps URL formats
        $patterns = [
            // Format: @lat,lng,zoom
            '/@(-?\d+\.\d+),(-?\d+\.\d+),/',
            // Format: ll=lat,lng
            '/ll=(-?\d+\.\d+),(-?\d+\.\d+)/',
            // Format: !3d and !4d
            '/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $mapsUrl, $matches)) {
                return [
                    'latitude' => (float) $matches[1],
                    'longitude' => (float) $matches[2]
                ];
            }
        }

        return null;
    }
}

if (!function_exists('generate_branch_qr_code')) {
    /**
     * Generate QR code URL for branch contact information
     */
    function generate_branch_qr_code($branch, $size = '200x200')
    {
        if (!$branch) {
            return null;
        }

        // Create vCard data for QR code
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "ORG:" . ($branch->business->business_name ?? '') . "\n";
        $vcard .= "FN:" . $branch->branch_name . "\n";
        $vcard .= "ADR:;;;" . $branch->branch_address . ";;;;\n";
        
        if ($branch->branch_phone) {
            $vcard .= "TEL:" . $branch->branch_phone . "\n";
        }
        
        if ($branch->branch_google_maps_link) {
            $vcard .= "URL:" . $branch->branch_google_maps_link . "\n";
        }
        
        $vcard .= "NOTE:Jam Operasional: " . $branch->branch_operational_hours . "\n";
        $vcard .= "END:VCARD";

        // Generate QR code URL using QR Server API
        return "https://api.qrserver.com/v1/create-qr-code/?size=" . $size . "&data=" . urlencode($vcard);
    }
}

if (!function_exists('get_branch_operational_status')) {
    /**
     * Check if branch is currently open based on operational hours
     * This is a simple implementation - you might want to enhance it
     */
    function get_branch_operational_status($operationalHours)
    {
        if (empty($operationalHours)) {
            return ['status' => 'unknown', 'message' => 'Jam operasional tidak tersedia'];
        }

        // Simple check for "24 jam" or similar
        if (stripos($operationalHours, '24') !== false) {
            return ['status' => 'open', 'message' => 'Buka 24 jam'];
        }

        // For more complex parsing, you would need to implement time parsing
        // This is just a placeholder
        return ['status' => 'unknown', 'message' => $operationalHours];
    }
}