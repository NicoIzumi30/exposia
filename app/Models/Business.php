<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Business extends Model
{
    use HasFactory, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'main_address',
        'main_operational_hours',
        'google_maps_link',
        'logo_url',
        'short_description',
        'full_description',
        'full_story',
        'hero_image_url',
        'about_image',
        'public_url',
        'publish_status',
        'qr_code',
        'progress_completion',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'publish_status' => 'boolean',
            'progress_completion' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the user that owns the business.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branches for the business.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'business_id');
    }

    /**
     * Get the testimonials for the business.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get the highlights for the business.
     */
    public function highlights(): HasMany
    {
        return $this->hasMany(BusinessHighlight::class);
    }

    /**
     * Get the template for the business.
     */
    public function template(): HasOne
    {
        return $this->hasOne(BusinessTemplate::class);
    }

    /**
     * Get the sections for the business.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(BusinessSection::class);
    }

    /**
     * Get the visitors for the business.
     */
    public function visitors(): HasMany
    {
        return $this->hasMany(BusinessVisitor::class);
    }

    // ========================================
    // ACCESSORS & ATTRIBUTES
    // ========================================

    /**
     * Get total branches count
     */
    public function getBranchesCountAttribute(): int
    {
        return $this->branches()->count();
    }

    /**
     * Get branches with contact information
     */
    public function getBranchesWithContactAttribute()
    {
        return $this->branches()->withPhone()->get();
    }

    /**
     * Get branches with Google Maps links
     */
    public function getBranchesWithMapsAttribute()
    {
        return $this->branches()->withMapsLink()->get();
    }

    /**
     * Get main branch (first created branch)
     */
    public function getMainBranchAttribute(): ?Branch
    {
        return $this->branches()->oldest()->first();
    }

    /**
     * Get branch statistics
     */
    public function getBranchStatsAttribute(): array
    {
        return get_branch_stats($this);
    }

    // ========================================
    // BUSINESS HELPER METHODS
    // ========================================

    /**
     * Get business logo URL with fallback
     * 
     * @return string
     */
    public function getLogoUrl()
    {
        return business_logo_url($this);
    }

    /**
     * Get formatted business address
     * 
     * @param int $maxLength
     * @return string
     */
    public function getFormattedAddress($maxLength = 100)
    {
        return format_business_address($this->main_address, $maxLength);
    }

    /**
     * Get business status badge HTML
     * 
     * @return string
     */
    public function getStatusBadge()
    {
        return business_status_badge($this);
    }

    /**
     * Get business status as text
     * 
     * @return string
     */
    public function getStatusText()
    {
        return get_business_status_text($this);
    }

    /**
     * Get business status color
     * 
     * @return string
     */
    public function getStatusColor()
    {
        return get_business_status_color($this);
    }

    /**
     * Get operational hours as array
     * 
     * @return array
     */
    public function getOperationalHoursArray()
    {
        return business_operational_hours_array($this->main_operational_hours);
    }

    /**
     * Calculate and update progress completion
     * 
     * @return int
     */
    public function updateProgressCompletion()
    {
        $completion = business_completion($this);
        $this->update(['progress_completion' => $completion]);

        return $completion;
    }

    /**
     * Calculate business completion including branches
     */
    public function calculateCompletionWithBranches(): int
    {
        $baseCompletion = business_completion($this);

        // Add 5% bonus if business has at least one branch
        if ($this->branches_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 5);
        }

        return $baseCompletion;
    }

    /**
     * Generate and set business URL
     * 
     * @return string|null
     */
    public function generateBusinessUrl()
    {
        if (empty($this->business_name)) {
            return null;
        }

        $slug = generate_business_url($this->business_name, $this->id);
        $fullUrl = url('/' . $slug);

        $this->update(['public_url' => $fullUrl]);

        return $fullUrl;
    }

    /**
     * Generate QR code for business URL
     * 
     * @param int $size
     * @return string|null
     */
    public function generateQrCode($size = 200)
    {
        if (!$this->public_url) {
            return null;
        }

        $qrCodeUrl = generate_qr_code_url($this->public_url, $size);
        $this->update(['qr_code' => $qrCodeUrl]);

        return $qrCodeUrl;
    }

    // ========================================
    // STATUS MANAGEMENT METHODS
    // ========================================

    /**
     * Check if business is published
     * 
     * @return bool
     */
    public function isPublished()
    {
        return $this->publish_status === true;
    }

    /**
     * Check if business is draft
     * 
     * @return bool
     */
    public function isDraft()
    {
        return $this->publish_status === false;
    }

    /**
     * Publish the business
     * 
     * @return bool
     */
    public function publish()
    {
        return $this->update(['publish_status' => true]);
    }

    /**
     * Unpublish the business (set to draft)
     * 
     * @return bool
     */
    public function unpublish()
    {
        return $this->update(['publish_status' => false]);
    }

    /**
     * Check if business is ready to publish
     * 
     * @return bool
     */
    public function isReadyToPublish()
    {
        return $this->progress_completion >= 80;
    }

    /**
     * Check if business has complete branch information
     */
    public function hasCompleteBranchInfo(): bool
    {
        if ($this->branches_count === 0) {
            return false;
        }

        return $this->branches->every(function ($branch) {
            return $branch->isComplete();
        });
    }

    // ========================================
    // COMPLETION & ANALYTICS METHODS
    // ========================================

    /**
     * Get business completion status
     * 
     * @return array
     */
    public function getCompletionStatus()
    {
        $fields = [
            'basic_info' => [
                'label' => 'Informasi Dasar',
                'completed' => !empty($this->business_name) && !empty($this->main_address) && !empty($this->main_operational_hours),
                'fields' => ['business_name', 'main_address', 'main_operational_hours']
            ],
            'descriptions' => [
                'label' => 'Deskripsi',
                'completed' => !empty($this->short_description) && !empty($this->full_description),
                'fields' => ['short_description', 'full_description']
            ],
            'logo' => [
                'label' => 'Logo',
                'completed' => !empty($this->logo_url),
                'fields' => ['logo_url']
            ],
            'location' => [
                'label' => 'Lokasi',
                'completed' => !empty($this->google_maps_link),
                'fields' => ['google_maps_link']
            ],
            'content' => [
                'label' => 'Konten',
                'completed' => $this->products()->count() > 0 || $this->galleries()->count() > 0,
                'fields' => ['products', 'galleries']
            ]
        ];

        $totalFields = count($fields);
        $completedFields = collect($fields)->filter(function ($field) {
            return $field['completed'];
        })->count();

        return [
            'fields' => $fields,
            'total' => $totalFields,
            'completed' => $completedFields,
            'percentage' => $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0
        ];
    }

    /**
     * Get business analytics data
     * 
     * @param int $days
     * @return array
     */
    public function getAnalyticsData($days = 30)
    {
        $startDate = now()->subDays($days);

        $visitors = $this->visitors()
            ->where('created_at', '>=', $startDate)
            ->get();

        $dailyVisitors = $visitors->groupBy(function ($visitor) {
            return $visitor->created_at->format('Y-m-d');
        })->map(function ($dayVisitors) {
            return $dayVisitors->count();
        });

        return [
            'total_visitors' => $visitors->count(),
            'unique_visitors' => $visitors->unique('ip_address')->count(),
            'daily_visitors' => $dailyVisitors,
            'average_daily' => $dailyVisitors->avg() ?: 0,
            'peak_day' => $dailyVisitors->isNotEmpty() ? $dailyVisitors->keys()->first() : null,
            'growth_rate' => $this->calculateGrowthRate($days)
        ];
    }

    /**
     * Calculate visitor growth rate
     * 
     * @param int $days
     * @return float
     */
    private function calculateGrowthRate($days = 30)
    {
        $currentPeriod = $this->visitors()
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        $previousPeriod = $this->visitors()
            ->where('created_at', '>=', now()->subDays($days * 2))
            ->where('created_at', '<', now()->subDays($days))
            ->count();

        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 2);
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope for published businesses
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('publish_status', true);
    }

    /**
     * Scope for draft businesses
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('publish_status', false);
    }

    /**
     * Scope for businesses with minimum completion percentage
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $percentage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMinCompletion($query, $percentage = 50)
    {
        return $query->where('progress_completion', '>=', $percentage);
    }

    // ========================================
    // URL & SEO METHODS
    // ========================================

    /**
     * Get full public URL for the business
     * 
     * @return string|null
     */
    public function getFullPublicUrl()
    {
        if (!$this->public_url) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->public_url, FILTER_VALIDATE_URL)) {
            return $this->public_url;
        }

        // Otherwise, prepend the app URL
        return url($this->public_url);
    }

    /**
     * Get business slug from public URL
     * 
     * @return string|null
     */
    public function getSlug()
    {
        if (!$this->public_url) {
            return null;
        }

        return basename(parse_url($this->public_url, PHP_URL_PATH));
    }

    /**
     * Update business SEO meta data
     * 
     * @return void
     */
    public function updateSeoMeta()
    {
        // This could be used to update SEO-related fields
        // when business information changes
        $seoTitle = $this->business_name;
        $seoDescription = $this->short_description;
        $seoKeywords = implode(', ', [
            $this->business_name,
            'bisnis',
            'usaha',
            // Add category or other relevant keywords
        ]);

        // Update if you have SEO meta fields in your business table
        // $this->update([
        //     'seo_title' => $seoTitle,
        //     'seo_description' => $seoDescription,
        //     'seo_keywords' => $seoKeywords
        // ]);
    }

    /**
     * Generate business schema.org JSON-LD data
     * 
     * @return array
     */
    public function getSchemaOrgData()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $this->business_name,
            'description' => $this->short_description,
            'url' => $this->getFullPublicUrl(),
        ];

        if ($this->main_address) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->main_address
            ];
        }

        if ($this->main_operational_hours) {
            $schema['openingHours'] = $this->main_operational_hours;
        }

        if ($this->logo_url) {
            $schema['logo'] = $this->getLogoUrl();
            $schema['image'] = $this->getLogoUrl();
        }

        return $schema;
    }
    // Add these methods to your existing Business model

    /**
     * Relationship: Business has many Products
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    /**
     * Get total products count
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Get pinned products
     */
    public function getPinnedProductsAttribute()
    {
        return $this->products()->pinned()->defaultOrder()->get();
    }

    /**
     * Get products with images
     */
    public function getProductsWithImagesAttribute()
    {
        return $this->products()->withImages()->get();
    }

    /**
     * Get featured products (pinned + with images)
     */
    public function getFeaturedProductsAttribute()
    {
        return $this->products()
            ->pinned()
            ->withImages()
            ->defaultOrder()
            ->limit(6)
            ->get();
    }

    /**
     * Get latest products
     */
    public function getLatestProductsAttribute($limit = 6)
    {
        return $this->products()
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get product statistics
     */
    public function getProductStatsAttribute(): array
    {
        return get_product_stats($this);
    }

    /**
     * Get average product price
     */
    public function getAverageProductPriceAttribute(): float
    {
        return $this->products()->avg('product_price') ?: 0;
    }

    /**
     * Get price range of products
     */
    public function getProductPriceRangeAttribute(): array
    {
        $products = $this->products();

        return [
            'min' => $products->min('product_price') ?: 0,
            'max' => $products->max('product_price') ?: 0
        ];
    }

    /**
     * Check if business has products
     */
    public function hasProducts(): bool
    {
        return $this->products_count > 0;
    }

    /**
     * Check if business has complete product information
     */
    public function hasCompleteProductInfo(): bool
    {
        if ($this->products_count === 0) {
            return false;
        }

        return $this->products->every(function ($product) {
            return $product->isComplete();
        });
    }

    /**
     * Get product completion rate
     */
    public function getProductCompletionRate(): int
    {
        if ($this->products_count === 0) {
            return 0;
        }

        $completeProducts = $this->products->filter(function ($product) {
            return $product->isComplete();
        })->count();

        return round(($completeProducts / $this->products_count) * 100);
    }

    /**
     * Calculate business completion including products
     */
    public function calculateCompletionWithProducts(): int
    {
        $baseCompletion = business_completion($this);

        // Add bonus points for having products
        if ($this->products_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 5);

            // Add bonus for product completion
            $productCompletionRate = $this->getProductCompletionRate();
            $productBonus = round($productCompletionRate * 0.1); // Max 10 points
            $baseCompletion = min(100, $baseCompletion + $productBonus);
        }

        return $baseCompletion;
    }

    /**
     * Get most expensive product
     */
    public function getMostExpensiveProductAttribute(): ?Product
    {
        return $this->products()->orderBy('product_price', 'desc')->first();
    }

    /**
     * Get cheapest product
     */
    public function getCheapestProductAttribute(): ?Product
    {
        return $this->products()->orderBy('product_price', 'asc')->first();
    }

    /**
     * Search products in this business
     */
    public function searchProducts(string $query, int $limit = 10)
    {
        return $this->products()
            ->search($query)
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get products by price range
     */
    public function getProductsByPriceRange($minPrice = null, $maxPrice = null, $limit = 20)
    {
        return $this->products()
            ->priceRange($minPrice, $maxPrice)
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get random products for showcase
     */
    public function getRandomProducts(int $count = 4)
    {
        return $this->products()
            ->withImages()
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    /**
     * Get product categories (if you have categories in the future)
     */
    public function getProductCategoriesAttribute(): array
    {
        // This would be useful if you add categories later
        // For now, return empty array
        return [];
    }

    /**
     * Check if business can add more products (if you want to set limits)
     */
    public function canAddMoreProducts(): bool
    {
        // You can set a limit here if needed
        $maxProducts = 100; // or get from config
        return $this->products_count < $maxProducts;
    }

    /**
     * Get products for sitemap generation
     */
    public function getProductsForSitemap()
    {
        return $this->products()
            ->whereNotNull('product_image')
            ->defaultOrder()
            ->get(['id', 'product_name', 'updated_at']);
    }

    /**
     * Generate business showcase data including products
     */
    public function getShowcaseData(): array
    {
        return [
            'business' => [
                'name' => $this->business_name,
                'description' => $this->short_description,
                'logo' => $this->logo_url,
                'url' => $this->public_url
            ],
            'stats' => [
                'products_count' => $this->products_count,
                'branches_count' => $this->branches_count ?? 0,
                'completion_rate' => $this->calculateCompletionWithProducts()
            ],
            'featured_products' => $this->featured_products->map(function ($product) {
                return $product->getCardData();
            }),
            'price_range' => $this->product_price_range
        ];
    }
// ========================================
    // SUPER SIMPLE GALLERY METHODS
    // ========================================
    
    /**
     * Get the galleries for the business.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'business_id');
    }
    
    /**
     * Get total galleries count
     */
    public function getGalleriesCountAttribute(): int
    {
        return $this->galleries()->count();
    }
    
    /**
     * Get latest galleries (newest first)
     */
    public function getLatestGalleriesAttribute($limit = 6)
    {
        return $this->galleries()
                    ->latest()
                    ->limit($limit)
                    ->get();
    }
    
    /**
     * Get random galleries for showcase
     */
    public function getRandomGalleriesAttribute($limit = 4)
    {
        return $this->galleries()
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();
    }
    
    /**
     * Get simple gallery stats
     */
    public function getGalleryStatsAttribute(): array
    {
        $count = $this->galleries_count;
        
        return [
            'total' => $count,
            'remaining' => max(0, 8 - $count), // Max 8 images
            'can_upload' => $count < 8,
        ];
    }
    
    /**
     * Check if business has galleries
     */
    public function hasGalleries(): bool
    {
        return $this->galleries_count > 0;
    }
    
    /**
     * Get first gallery image
     */
    public function getMainGalleryImageAttribute(): ?Gallery
    {
        return $this->galleries()->latest()->first();
    }
    
    /**
     * Get simple gallery showcase data for public display
     */
    public function getGalleryShowcaseAttribute(): array
    {
        $galleries = $this->galleries()->latest()->take(8)->get();
        
        return [
            'galleries' => $galleries->map(function ($gallery) {
                return $gallery->getSimpleData();
            }),
            'total_count' => $this->galleries_count,
            'has_more' => $this->galleries_count > 8,
        ];
    }
    
    /**
     * Get galleries for sitemap generation
     */
    public function getGalleriesForSitemapAttribute()
    {
        return $this->galleries()
                    ->latest()
                    ->get(['id', 'gallery_image', 'updated_at']);
    }
    
    /**
     * Calculate business completion including galleries (simplified)
     */
    public function calculateCompletionWithGalleries(): int
    {
        $baseCompletion = business_completion($this);
    
        // Add bonus points for having galleries
        if ($this->galleries_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 10); // Simple 10 point bonus
        }
    
        return $baseCompletion;
    }
    
    /**
     * Get business hero image (logo or first gallery image)
     */
    public function getHeroImageUrlAttribute(): string
    {
        // Priority: logo_url > main_gallery_image > fallback
        if (!empty($this->logo_url)) {
            return $this->getLogoUrl();
        }
    
        if ($this->main_gallery_image) {
            return $this->main_gallery_image->image_url;
        }
    
        return asset('images/placeholder-business.jpg');
    }
    
    /**
     * Get all media files (products + gallery) - simplified
     */
    public function getAllMediaFilesAttribute(): array
    {
        $media = [];
    
        // Add product images
        foreach ($this->products as $product) {
            if ($product->product_image) {
                $media[] = [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'url' => $product->image_url,
                ];
            }
        }
    
        // Add gallery images
        foreach ($this->galleries as $gallery) {
            if ($gallery->gallery_image) {
                $media[] = [
                    'type' => 'gallery',
                    'id' => $gallery->id,
                    'name' => $gallery->display_name,
                    'url' => $gallery->image_url,
                ];
            }
        }
    
        return $media;
    }
    
    /**
     * Check if business has sufficient media content
     */
    public function hasSufficientMediaContent(): bool
    {
        // Consider sufficient if has at least 3 products with images OR 3 gallery images
        $productImages = $this->products()->whereNotNull('product_image')->count();
        $galleryImages = $this->galleries_count;
    
        return ($productImages >= 3) || ($galleryImages >= 3) || (($productImages + $galleryImages) >= 5);
    }
    
    /**
     * Get simple media content summary
     */
    public function getMediaSummaryAttribute(): array
    {
        return [
            'products_with_images' => $this->products()->whereNotNull('product_image')->count(),
            'total_products' => $this->products_count,
            'gallery_images' => $this->galleries_count,
            'total_media_files' => count($this->all_media_files),
            'has_sufficient_content' => $this->hasSufficientMediaContent(),
        ];
    }
    
    /**
     * Update business completion including all media
     */
    public function updateCompletionWithMedia(): int
    {
        $completion = $this->calculateCompletionWithGalleries();
    
        // Update the stored completion
        $this->update(['progress_completion' => $completion]);
    
        return $completion;
    }
    
    /**
     * Get business showcase data including galleries (simplified)
     */
    public function getCompleteShowcaseDataAttribute(): array
    {
        return [
            'business' => [
                'name' => $this->business_name,
                'description' => $this->short_description,
                'logo' => $this->logo_url,
                'hero_image' => $this->hero_image_url,
                'url' => $this->public_url,
            ],
            'stats' => [
                'products_count' => $this->products_count,
                'galleries_count' => $this->galleries_count,
                'branches_count' => $this->branches_count ?? 0,
                'completion_rate' => $this->calculateCompletionWithGalleries(),
            ],
            'featured_content' => [
                'products' => $this->featured_products->take(6)->map(function ($product) {
                    return $product->getCardData();
                }),
                'galleries' => $this->latest_galleries->map(function ($gallery) {
                    return $gallery->getSimpleData();
                }),
            ],
            'media_summary' => $this->media_summary,
        ];
    }
    
    /**
     * Check if business can add more galleries
     */
    public function canAddMoreGalleries(): bool
    {
        return $this->galleries_count < 8; // Max 8 galleries
    }
    
    /**
     * Get simple gallery quota information
     */
    public function getGalleryQuotaInfoAttribute(): array
    {
        $count = $this->galleries_count;
        $max = 8;
        $remaining = max(0, $max - $count);
        $percentage = $max > 0 ? round(($count / $max) * 100) : 0;
    
        return [
            'current' => $count,
            'max' => $max,
            'remaining' => $remaining,
            'percentage' => $percentage,
            'can_upload' => $count < $max,
            'is_full' => $count >= $max,
        ];
    }
    
    // ========================================
    // SIMPLIFIED COMPLETION CALCULATION
    // ========================================
    
    /**
     * Simple completion status including galleries
     */
    public function getSimpleCompletionStatusAttribute(): array
    {
        $fields = [
            'basic_info' => [
                'label' => 'Informasi Dasar',
                'completed' => !empty($this->business_name) && !empty($this->main_address) && !empty($this->main_operational_hours),
                'weight' => 25
            ],
            'descriptions' => [
                'label' => 'Deskripsi',
                'completed' => !empty($this->short_description) && !empty($this->full_description),
                'weight' => 20
            ],
            'logo' => [
                'label' => 'Logo',
                'completed' => !empty($this->logo_url),
                'weight' => 15
            ],
            'location' => [
                'label' => 'Lokasi',
                'completed' => !empty($this->google_maps_link),
                'weight' => 10
            ],
            'products' => [
                'label' => 'Produk',
                'completed' => $this->products()->count() >= 3,
                'weight' => 20
            ],
            'galleries' => [
                'label' => 'Galeri',
                'completed' => $this->galleries()->count() >= 3,
                'weight' => 10
            ]
        ];
    
        $totalWeight = array_sum(array_column($fields, 'weight'));
        $completedWeight = 0;
    
        foreach ($fields as $field) {
            if ($field['completed']) {
                $completedWeight += $field['weight'];
            }
        }
    
        $percentage = $totalWeight > 0 ? round(($completedWeight / $totalWeight) * 100) : 0;
    
        return [
            'fields' => $fields,
            'total_weight' => $totalWeight,
            'completed_weight' => $completedWeight,
            'percentage' => $percentage,
            'next_steps' => $this->getSimpleNextSteps($fields),
        ];
    }
    
    /**
     * Get next steps for completion (simplified)
     */
    private function getSimpleNextSteps(array $fields): array
    {
        $nextSteps = [];
        
        foreach ($fields as $key => $field) {
            if (!$field['completed']) {
                switch ($key) {
                    case 'basic_info':
                        $nextSteps[] = 'Lengkapi informasi dasar bisnis';
                        break;
                    case 'descriptions':
                        $nextSteps[] = 'Tambahkan deskripsi bisnis';
                        break;
                    case 'logo':
                        $nextSteps[] = 'Upload logo bisnis';
                        break;
                    case 'location':
                        $nextSteps[] = 'Tambahkan link Google Maps';
                        break;
                    case 'products':
                        $nextSteps[] = 'Upload minimal 3 produk';
                        break;
                    case 'galleries':
                        $nextSteps[] = 'Upload minimal 3 foto galeri';
                        break;
                }
            }
        }
    
        return array_slice($nextSteps, 0, 3); // Return top 3 next steps
    }
    
    // ========================================
    // SIMPLE QUERY SCOPES
    // ========================================
    
    /**
     * Scope for businesses with galleries
     */
    public function scopeWithGalleries($query)
    {
        return $query->has('galleries');
    }
    
    /**
     * Scope for businesses with sufficient media content
     */
    public function scopeWithSufficientMediaContent($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('products', function ($productQuery) {
                $productQuery->whereNotNull('product_image');
            }, '>=', 3)
            ->orWhereHas('galleries', null, '>=', 3);
        });
    }
}