<?php

if (!function_exists('format_currency')) {
    /**
     * Format number to Indonesian Rupiah currency
     */
    function format_currency($amount = 0, $showSymbol = true, $showDecimals = false)
    {
        if ($amount === null || $amount === '') {
            $amount = 0;
        }

        $amount = (float) $amount;
        
        if ($showDecimals && $amount != floor($amount)) {
            $formatted = number_format($amount, 2, ',', '.');
        } else {
            $formatted = number_format($amount, 0, ',', '.');
        }

        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('parse_currency')) {
    /**
     * Parse currency string to numeric value
     */
    function parse_currency($currencyString)
    {
        if (empty($currencyString)) {
            return 0;
        }

        // Remove currency symbols and formatting
        $numeric = preg_replace('/[^\d,.-]/', '', $currencyString);
        
        // Handle Indonesian decimal separator
        $numeric = str_replace(['.', ','], ['', '.'], $numeric);
        
        return (float) $numeric;
    }
}

if (!function_exists('get_product_stats')) {
    /**
     * Get product statistics for a business
     */
    function get_product_stats($business)
    {
        if (!$business) {
            return [
                'total' => 0,
                'pinned' => 0,
                'with_images' => 0,
                'with_wa_links' => 0,
                'avg_price' => 0,
                'completion_rate' => 0
            ];
        }

        $products = $business->products;
        $total = $products->count();
        $pinned = $products->where('is_pinned', true)->count();
        $withImages = $products->whereNotNull('product_image')->count();
        $withWaLinks = $products->whereNotNull('product_wa_link')->count();
        
        // Calculate average price
        $avgPrice = $total > 0 ? $products->avg('product_price') : 0;
        
        // Calculate completion rate based on products with all fields filled
        $completeProducts = $products->filter(function ($product) {
            return $product->isComplete();
        })->count();
        
        $completionRate = $total > 0 ? round(($completeProducts / $total) * 100) : 0;

        return [
            'total' => $total,
            'pinned' => $pinned,
            'with_images' => $withImages,
            'with_wa_links' => $withWaLinks,
            'avg_price' => $avgPrice,
            'completion_rate' => $completionRate
        ];
    }
}

if (!function_exists('generate_product_slug')) {
    /**
     * Generate unique slug for product
     */
    function generate_product_slug($productName, $businessId = null, $excludeId = null)
    {
        $baseSlug = \Illuminate\Support\Str::slug($productName);
        $slug = $baseSlug;
        $counter = 1;

        $query = \App\Models\Product::where('product_name', 'like', $slug . '%');
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->where('product_name', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('optimize_product_image')) {
    /**
     * Optimize product image for web display
     */
    function optimize_product_image($imagePath, $maxWidth = 800, $maxHeight = 800, $quality = 85)
    {
        if (!file_exists($imagePath)) {
            return false;
        }

        $imageInfo = getimagesize($imagePath);
        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($imagePath);
                break;
            default:
                return false;
        }

        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Calculate new dimensions
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        
        if ($ratio < 1) {
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);

            $optimizedImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($mime === 'image/png') {
                imagealphablending($optimizedImage, false);
                imagesavealpha($optimizedImage, true);
            }

            imagecopyresampled(
                $optimizedImage, $image,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );

            // Save optimized image
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($optimizedImage, $imagePath, $quality);
                    break;
                case 'image/png':
                    imagepng($optimizedImage, $imagePath, round((100 - $quality) / 10));
                    break;
                case 'image/webp':
                    imagewebp($optimizedImage, $imagePath, $quality);
                    break;
            }

            imagedestroy($optimizedImage);
        }

        imagedestroy($image);
        return true;
    }
}

if (!function_exists('get_price_range_options')) {
    /**
     * Get predefined price range options for filtering
     */
    function get_price_range_options()
    {
        return [
            ['label' => 'Semua Harga', 'min' => null, 'max' => null],
            ['label' => 'Di bawah Rp 50.000', 'min' => 0, 'max' => 50000],
            ['label' => 'Rp 50.000 - Rp 100.000', 'min' => 50000, 'max' => 100000],
            ['label' => 'Rp 100.000 - Rp 250.000', 'min' => 100000, 'max' => 250000],
            ['label' => 'Rp 250.000 - Rp 500.000', 'min' => 250000, 'max' => 500000],
            ['label' => 'Rp 500.000 - Rp 1.000.000', 'min' => 500000, 'max' => 1000000],
            ['label' => 'Di atas Rp 1.000.000', 'min' => 1000000, 'max' => null],
        ];
    }
}

if (!function_exists('generate_product_qr_code')) {
    /**
     * Generate QR code URL for product sharing
     */
    function generate_product_qr_code($product, $size = '200x200')
    {
        if (!$product) {
            return null;
        }

        $productUrl = url('/product/' . $product->slug);
        
        // Generate QR code URL using QR Server API
        return "https://api.qrserver.com/v1/create-qr-code/?size=" . $size . "&data=" . urlencode($productUrl);
    }
}

if (!function_exists('format_product_description')) {
    /**
     * Format product description for display with line breaks
     */
    function format_product_description($description, $preserveLineBreaks = true)
    {
        if (empty($description)) {
            return '';
        }

        // Clean HTML tags
        $description = strip_tags($description);
        
        if ($preserveLineBreaks) {
            // Convert line breaks to <br> tags
            $description = nl2br($description);
        }

        return $description;
    }
}

if (!function_exists('get_product_image_sizes')) {
    /**
     * Get different image sizes for responsive display
     */
    function get_product_image_sizes($imagePath)
    {
        if (!$imagePath) {
            return [
                'thumbnail' => null,
                'medium' => null,
                'large' => null,
                'original' => null
            ];
        }

        $baseUrl = \Illuminate\Support\Facades\Storage::url($imagePath);
        $pathInfo = pathinfo($imagePath);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = $pathInfo['dirname'];

        return [
            'thumbnail' => $baseUrl, // 150x150
            'medium' => $baseUrl,    // 400x400  
            'large' => $baseUrl,     // 800x800
            'original' => $baseUrl   // Original size
        ];
    }
}

if (!function_exists('validate_product_data')) {
    /**
     * Validate product data before saving
     */
    function validate_product_data($data)
    {
        $errors = [];

        // Required fields
        if (empty($data['product_name'])) {
            $errors['product_name'] = 'Nama produk wajib diisi.';
        }

        if (empty($data['product_description'])) {
            $errors['product_description'] = 'Deskripsi produk wajib diisi.';
        }

        if (!isset($data['product_price']) || $data['product_price'] < 0) {
            $errors['product_price'] = 'Harga produk harus lebih dari 0.';
        }

        // Optional validations
        if (!empty($data['product_wa_link'])) {
            if (!preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $data['product_wa_link'])) {
                $errors['product_wa_link'] = 'Format nomor WhatsApp tidak valid.';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

if (!function_exists('get_popular_products')) {
    /**
     * Get popular products based on views or other metrics
     */
    function get_popular_products($businessId = null, $limit = 10)
    {
        $query = \App\Models\Product::with('business')
            ->defaultOrder();

        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        return $query->limit($limit)->get();
    }
}

if (!function_exists('calculate_discount_price')) {
    /**
     * Calculate discounted price
     */
    function calculate_discount_price($originalPrice, $discountPercent)
    {
        if ($discountPercent <= 0 || $discountPercent >= 100) {
            return $originalPrice;
        }

        $discountAmount = ($originalPrice * $discountPercent) / 100;
        return $originalPrice - $discountAmount;
    }
}

if (!function_exists('format_product_attributes')) {
    /**
     * Format product attributes for display
     */
    function format_product_attributes($attributes)
    {
        if (empty($attributes) || !is_array($attributes)) {
            return '';
        }

        $formatted = [];
        foreach ($attributes as $key => $value) {
            $formatted[] = ucfirst($key) . ': ' . $value;
        }

        return implode(' | ', $formatted);
    }
}

if (!function_exists('generate_product_share_text')) {
    /**
     * Generate text for sharing product on social media
     */
    function generate_product_share_text($product)
    {
        if (!$product) {
            return '';
        }

        $text = "🛍️ {$product->product_name}\n\n";
        $text .= "💰 Harga: {$product->formatted_price}\n";
        $text .= "📝 {$product->short_description}\n\n";
        
        if ($product->business) {
            $text .= "🏪 {$product->business->business_name}\n";
        }
        
        $text .= "🔗 {$product->share_url}";

        return $text;
    }
}