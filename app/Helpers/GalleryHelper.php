<?php

if (!function_exists('format_file_size')) {
    /**
     * Format file size in human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function format_file_size($bytes, $precision = 2)
    {
        if ($bytes == 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor(log($bytes, 1024));
        $size = $bytes / pow(1024, $factor);

        return round($size, $precision) . ' ' . $units[$factor];
    }
}

if (!function_exists('gallery_image_url')) {
    /**
     * Get gallery image URL with fallback
     *
     * @param \App\Models\Gallery|string|null $gallery
     * @return string
     */
    function gallery_image_url($gallery)
    {
        if (is_string($gallery)) {
            // If string path is passed
            if (empty($gallery)) {
                return asset('images/placeholder-gallery.jpg');
            }
            
            if (filter_var($gallery, FILTER_VALIDATE_URL)) {
                return $gallery;
            }
            
            return \Storage::url($gallery);
        }

        if ($gallery instanceof \App\Models\Gallery) {
            return $gallery->image_url;
        }

        return asset('images/placeholder-gallery.jpg');
    }
}

if (!function_exists('gallery_thumbnail_url')) {
    /**
     * Get gallery thumbnail URL
     *
     * @param \App\Models\Gallery|string|null $gallery
     * @param string $size
     * @return string
     */
    function gallery_thumbnail_url($gallery, $size = 'medium')
    {
        // For now, return the same as image URL
        // In the future, this could generate actual thumbnails
        return gallery_image_url($gallery);
    }
}

if (!function_exists('validate_image_file')) {
    /**
     * Validate image file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $options
     * @return array
     */
    function validate_image_file($file, $options = [])
    {
        $defaultOptions = [
            'max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
            'min_width' => 200,
            'min_height' => 200,
            'max_width' => 4000,
            'max_height' => 4000,
        ];

        $options = array_merge($defaultOptions, $options);
        $errors = [];

        // Check if file exists
        if (!$file || !$file->isValid()) {
            $errors[] = 'File tidak valid atau tidak ditemukan.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Check file type
        if (!in_array($file->getMimeType(), $options['allowed_types'])) {
            $errors[] = 'Format file harus JPEG, JPG, PNG, atau WebP.';
        }

        // Check file size
        if ($file->getSize() > $options['max_size']) {
            $maxSizeMB = $options['max_size'] / (1024 * 1024);
            $errors[] = "Ukuran file maksimal {$maxSizeMB}MB.";
        }

        // Check image dimensions
        if (in_array($file->getMimeType(), $options['allowed_types'])) {
            $imageInfo = getimagesize($file->getRealPath());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($width < $options['min_width'] || $height < $options['min_height']) {
                    $errors[] = "Ukuran gambar minimal {$options['min_width']}x{$options['min_height']} piksel.";
                }

                if ($width > $options['max_width'] || $height > $options['max_height']) {
                    $errors[] = "Ukuran gambar maksimal {$options['max_width']}x{$options['max_height']} piksel.";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

if (!function_exists('optimize_gallery_image')) {
    /**
     * Optimize gallery image for web
     *
     * @param string $imagePath
     * @param array $options
     * @return bool
     */
    function optimize_gallery_image($imagePath, $options = [])
    {
        $defaultOptions = [
            'max_width' => 1200,
            'max_height' => 1200,
            'quality' => 85,
        ];

        $options = array_merge($defaultOptions, $options);

        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            if (!file_exists($fullPath)) {
                return false;
            }

            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                return false;
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Skip optimization if image is already small enough
            if ($width <= $options['max_width'] && $height <= $options['max_height']) {
                return true;
            }

            // Calculate new dimensions
            $ratio = min($options['max_width'] / $width, $options['max_height'] / $height);
            $newWidth = round($width * $ratio);
            $newHeight = round($height * $ratio);

            // Create image resource based on type
            switch ($mimeType) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($fullPath);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($fullPath);
                    break;
                case 'image/webp':
                    $source = imagecreatefromwebp($fullPath);
                    break;
                default:
                    return false;
            }

            if (!$source) {
                return false;
            }

            // Create new image
            $destination = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG and WebP
            if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
                imagealphablending($destination, false);
                imagesavealpha($destination, true);
                $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
                imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize image
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // Save optimized image
            switch ($mimeType) {
                case 'image/jpeg':
                    imagejpeg($destination, $fullPath, $options['quality']);
                    break;
                case 'image/png':
                    imagepng($destination, $fullPath, round(9 * (100 - $options['quality']) / 100));
                    break;
                case 'image/webp':
                    imagewebp($destination, $fullPath, $options['quality']);
                    break;
            }

            // Clean up memory
            imagedestroy($source);
            imagedestroy($destination);

            return true;

        } catch (\Exception $e) {
            \Log::error('Image optimization error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('generate_gallery_filename')) {
    /**
     * Generate unique filename for gallery image
     *
     * @param string $originalName
     * @param string $businessId
     * @return string
     */
    function generate_gallery_filename($originalName, $businessId = null)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Sanitize filename
        $name = preg_replace('/[^a-zA-Z0-9\-_]/', '', $name);
        $name = substr($name, 0, 30); // Limit length
        
        $timestamp = now()->format('YmdHis');
        $random = \Str::random(6);
        
        $filename = $name . '_' . $timestamp . '_' . $random;
        
        if ($businessId) {
            $filename = 'business_' . $businessId . '_' . $filename;
        }
        
        return $filename . '.' . strtolower($extension);
    }
}

if (!function_exists('get_gallery_stats')) {
    /**
     * Get gallery statistics for a business
     *
     * @param \App\Models\Business $business
     * @return array
     */
    function get_gallery_stats($business)
    {
        if (!$business) {
            return [
                'total' => 0,
                'featured' => 0,
                'with_captions' => 0,
                'total_size' => 0,
                'avg_completion' => 0,
                'recent_uploads' => 0,
            ];
        }

        $galleries = $business->galleries();
        $recentDate = now()->subDays(7);

        return [
            'total' => $galleries->count(),
            'featured' => $galleries->clone()->where('is_featured', true)->count(),
            'with_captions' => $galleries->clone()->whereNotNull('image_caption')
                                      ->where('image_caption', '!=', '')->count(),
            'total_size' => $galleries->get()->sum(function ($gallery) {
                return $gallery->file_size ?? 0;
            }),
            'avg_completion' => round($galleries->get()->avg(function ($gallery) {
                return $gallery->getCompletionPercentage();
            }) ?? 0),
            'recent_uploads' => $galleries->clone()->where('created_at', '>=', $recentDate)->count(),
        ];
    }
}

if (!function_exists('gallery_completion')) {
    /**
     * Calculate gallery completion percentage for a business
     *
     * @param \App\Models\Business $business
     * @return int
     */
    function gallery_completion($business)
    {
        if (!$business) {
            return 0;
        }

        $galleries = $business->galleries;
        
        if ($galleries->isEmpty()) {
            return 0;
        }

        $totalCompletion = $galleries->sum(function ($gallery) {
            return $gallery->getCompletionPercentage();
        });

        return round($totalCompletion / $galleries->count());
    }
}

if (!function_exists('get_featured_galleries')) {
    /**
     * Get featured galleries for a business
     *
     * @param \App\Models\Business $business
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_featured_galleries($business, $limit = 6)
    {
        if (!$business) {
            return collect();
        }

        return $business->galleries()
                       ->featured()
                       ->ordered()
                       ->limit($limit)
                       ->get();
    }
}

if (!function_exists('get_random_galleries')) {
    /**
     * Get random galleries for showcase
     *
     * @param \App\Models\Business $business
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_random_galleries($business, $limit = 4)
    {
        if (!$business) {
            return collect();
        }

        return $business->galleries()
                       ->inRandomOrder()
                       ->limit($limit)
                       ->get();
    }
}

if (!function_exists('gallery_grid_html')) {
    /**
     * Generate HTML for gallery grid
     *
     * @param \Illuminate\Database\Eloquent\Collection $galleries
     * @param array $options
     * @return string
     */
    function gallery_grid_html($galleries, $options = [])
    {
        $defaultOptions = [
            'show_captions' => true,
            'show_lightbox' => true,
            'grid_classes' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4',
            'item_classes' => 'aspect-square bg-gray-100 rounded-lg overflow-hidden',
        ];

        $options = array_merge($defaultOptions, $options);
        
        if ($galleries->isEmpty()) {
            return '<div class="text-center text-gray-500 py-8">Belum ada foto dalam galeri.</div>';
        }

        $html = '<div class="' . $options['grid_classes'] . '">';
        
        foreach ($galleries as $gallery) {
            $html .= '<div class="' . $options['item_classes'] . '">';
            
            if ($options['show_lightbox']) {
                $html .= '<img src="' . $gallery->image_url . '" ';
                $html .= 'alt="' . htmlspecialchars($gallery->image_alt_text ?: $gallery->image_caption ?: 'Gallery Image') . '" ';
                $html .= 'class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300" ';
                $html .= 'onclick="openLightbox(\'' . $gallery->image_url . '\', \'' . addslashes($gallery->image_caption ?: 'Untitled') . '\')">';
            } else {
                $html .= '<img src="' . $gallery->image_url . '" ';
                $html .= 'alt="' . htmlspecialchars($gallery->image_alt_text ?: $gallery->image_caption ?: 'Gallery Image') . '" ';
                $html .= 'class="w-full h-full object-cover">';
            }

            if ($options['show_captions'] && $gallery->image_caption) {
                $html .= '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2">';
                $html .= '<p class="text-sm">' . htmlspecialchars($gallery->image_caption) . '</p>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('validate_bulk_gallery_upload')) {
    /**
     * Validate bulk gallery upload
     *
     * @param array $files
     * @param array $options
     * @return array
     */
    function validate_bulk_gallery_upload($files, $options = [])
    {
        $defaultOptions = [
            'max_files' => 8,
            'max_total_size' => 50 * 1024 * 1024, // 50MB
            'max_file_size' => 5 * 1024 * 1024, // 5MB per file
        ];

        $options = array_merge($defaultOptions, $options);
        $errors = [];

        // Check file count
        if (count($files) > $options['max_files']) {
            $errors[] = "Maksimal {$options['max_files']} file dapat diupload sekaligus.";
        }

        // Check total size
        $totalSize = array_sum(array_map(function ($file) {
            return $file->getSize();
        }, $files));

        if ($totalSize > $options['max_total_size']) {
            $maxSizeMB = $options['max_total_size'] / (1024 * 1024);
            $errors[] = "Total ukuran semua file tidak boleh lebih dari {$maxSizeMB}MB.";
        }

        // Validate each file
        foreach ($files as $index => $file) {
            $validation = validate_image_file($file, [
                'max_size' => $options['max_file_size']
            ]);

            if (!$validation['valid']) {
                $errors[] = "File " . ($index + 1) . ": " . implode(' ', $validation['errors']);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'file_count' => count($files),
            'total_size' => $totalSize,
        ];
    }
}

if (!function_exists('create_gallery_lightbox_data')) {
    /**
     * Create lightbox data for galleries
     *
     * @param \Illuminate\Database\Eloquent\Collection $galleries
     * @return array
     */
    function create_gallery_lightbox_data($galleries)
    {
        return $galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'src' => $gallery->image_url,
                'title' => $gallery->image_caption ?: 'Untitled',
                'alt' => $gallery->image_alt_text ?: $gallery->image_caption ?: 'Gallery Image',
                'featured' => $gallery->is_featured,
                'created_at' => $gallery->created_at->format('d M Y'),
            ];
        })->values()->toArray();
    }
}

if (!function_exists('gallery_seo_meta')) {
    /**
     * Generate SEO meta data for gallery
     *
     * @param \App\Models\Business $business
     * @return array
     */
    function gallery_seo_meta($business)
    {
        if (!$business) {
            return [];
        }

        $featuredGalleries = get_featured_galleries($business, 4);
        $galleryCount = $business->galleries()->count();

        $title = "Galeri Foto {$business->business_name}";
        $description = "Lihat koleksi foto {$business->business_name}";
        
        if ($galleryCount > 0) {
            $description .= " dengan {$galleryCount} foto menarik";
        }

        $meta = [
            'title' => $title,
            'description' => $description,
            'keywords' => implode(', ', [
                $business->business_name,
                'galeri',
                'foto',
                'gambar',
                'produk',
                'layanan'
            ]),
        ];

        // Add featured image if available
        if ($featuredGalleries->isNotEmpty()) {
            $meta['image'] = $featuredGalleries->first()->image_url;
        }

        return $meta;
    }
}

if (!function_exists('cleanup_orphaned_gallery_images')) {
    /**
     * Clean up orphaned gallery images
     *
     * @return array
     */
    function cleanup_orphaned_gallery_images()
    {
        try {
            $galleryPath = 'gallery-images';
            $disk = \Storage::disk('public');
            
            if (!$disk->exists($galleryPath)) {
                return ['success' => true, 'cleaned' => 0, 'message' => 'Gallery directory does not exist.'];
            }

            $allFiles = $disk->allFiles($galleryPath);
            $galleryImages = \App\Models\Gallery::whereNotNull('gallery_image')
                                               ->pluck('gallery_image')
                                               ->toArray();

            $orphanedFiles = array_diff($allFiles, $galleryImages);
            $cleanedCount = 0;

            foreach ($orphanedFiles as $file) {
                if ($disk->delete($file)) {
                    $cleanedCount++;
                }
            }

            return [
                'success' => true,
                'cleaned' => $cleanedCount,
                'message' => "Cleaned up {$cleanedCount} orphaned gallery images."
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'cleaned' => 0,
                'message' => 'Error cleaning up files: ' . $e->getMessage()
            ];
        }
    }
}