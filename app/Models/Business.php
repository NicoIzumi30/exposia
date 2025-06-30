<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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
        'hero_image_secondary_url',
        'about_image',
        'about_image_secondary',
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
       return $this->attributes['hero_image_url'] ?? "";
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


    /**
     * Get next steps for completion (simplified)
     */


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

    // Add these methods to your existing Business.php model
    // (Add them in the RELATIONSHIPS section)

    /**
     * Get the testimonials for the business.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'business_id');
    }

    // Add these in the ACCESSORS & ATTRIBUTES section

    /**
     * Get total testimonials count
     */
    public function getTestimonialsCountAttribute(): int
    {
        return $this->testimonials()->count();
    }

    /**
     * Get latest testimonials
     */
    public function getLatestTestimonialsAttribute($limit = 5)
    {
        return $this->testimonials()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get random testimonials for showcase
     */
    public function getRandomTestimonialsAttribute($limit = 3)
    {
        return $this->testimonials()
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get testimonial statistics
     */
    public function getTestimonialStatsAttribute(): array
    {
        $count = $this->testimonials_count;
        $thisMonth = $this->testimonials()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $count,
            'this_month' => $thisMonth,
            'has_testimonials' => $count > 0,
        ];
    }

    // Add these in the BUSINESS HELPER METHODS section

    /**
     * Check if business has testimonials
     */
    public function hasTestimonials(): bool
    {
        return $this->testimonials_count > 0;
    }

    /**
     * Check if business has complete testimonial information
     */
    public function hasCompleteTestimonialInfo(): bool
    {
        if ($this->testimonials_count === 0) {
            return false;
        }

        return $this->testimonials->every(function ($testimonial) {
            return $testimonial->isComplete();
        });
    }

    /**
     * Get testimonials for public website display
     */
    public function getPublicTestimonials(int $limit = 6)
    {
        return $this->testimonials()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($testimonial) {
                return $testimonial->getDisplayData();
            });
    }

    // Update the completion calculation method to include testimonials

    /**
     * Calculate business completion including testimonials
     */
    public function calculateCompletionWithTestimonials(): int
    {
        $completion = $this->calculateCompletionWithGalleries(); // Use existing method

        // Add bonus points for having testimonials
        if ($this->testimonials_count > 0) {
            $completion = min(100, $completion + 5); // 5 point bonus

            // Additional bonus for having multiple testimonials
            if ($this->testimonials_count >= 3) {
                $completion = min(100, $completion + 5); // Additional 5 points
            }
        }

        return $completion;
    }

    // Add this to the SIMPLE COMPLETION CALCULATION section
    // Update the getSimpleCompletionStatusAttribute method to include testimonials:

    /**
     * Simple completion status including testimonials (updated)
     */


    // Update the getSimpleNextSteps method to include testimonials:

    /**
     * Get next steps for completion (updated with testimonials)
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
                    case 'testimonials':
                        $nextSteps[] = 'Tambahkan minimal 2 testimoni';
                        break;
                }
            }
        }

        return array_slice($nextSteps, 0, 3); // Return top 3 next steps
    }

    // Add scope for businesses with testimonials

    /**
     * Scope for businesses with testimonials
     */
    public function scopeWithTestimonials($query)
    {
        return $query->has('testimonials');
    }

    /**
     * Scope for businesses with sufficient testimonials
     */
    public function scopeWithSufficientTestimonials($query, int $minCount = 2)
    {
        return $query->whereHas('testimonials', null, '>=', $minCount);
    }


    // Add these methods to your existing Business.php model
    // (Add them in the RELATIONSHIPS section)

    /**
     * Get the highlights for the business.
     */
    public function highlights(): HasMany
    {
        return $this->hasMany(BusinessHighlight::class, 'business_id');
    }

    // Add these in the ACCESSORS & ATTRIBUTES section

    /**
     * Get total highlights count
     */
    public function getHighlightsCountAttribute(): int
    {
        return $this->highlights()->count();
    }

    /**
     * Get latest highlights
     */
    public function getLatestHighlightsAttribute($limit = 6)
    {
        return $this->highlights()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Get complete highlights (for public display)
     */
    public function getCompleteHighlightsAttribute()
    {
        return $this->highlights()
            ->get()
            ->filter(function ($highlight) {
                return $highlight->isComplete();
            });
    }

    /**
     * Get highlights statistics
     */
    public function getHighlightStatsAttribute(): array
    {
        $total = $this->highlights_count;
        $complete = $this->complete_highlights->count();

        return [
            'total' => $total,
            'complete' => $complete,
            'incomplete' => $total - $complete,
            'has_highlights' => $total > 0,
            'completion_rate' => $total > 0 ? round(($complete / $total) * 100) : 0,
        ];
    }

    /**
     * Get about image URL with fallback
     */
    public function getAboutImageUrlAttribute(): ?string
    {
        if (!$this->about_image) {
            return null;
        }

        return Storage::url($this->about_image);
    }

    /**
     * Check if business has about image
     */
    public function getHasAboutImageAttribute(): bool
    {
        return !empty($this->about_image) && Storage::disk('public')->exists($this->about_image);
    }

    // Add these in the BUSINESS HELPER METHODS section

    /**
     * Check if business has highlights
     */
    public function hasHighlights(): bool
    {
        return $this->highlights_count > 0;
    }

    /**
     * Check if business has sufficient highlights
     */
    public function hasSufficientHighlights(int $minCount = 3): bool
    {
        return $this->highlights_count >= $minCount;
    }

    public function getAboutImageSecondaryUrlAttribute(): ?string
    {
        if (!$this->about_image_secondary) {
            return null;
        }

        return Storage::url($this->about_image_secondary);
    }

    /**
     * Check if business has secondary about image
     */
    public function getHasAboutImageSecondaryAttribute(): bool
    {
        return !empty($this->about_image_secondary) && Storage::disk('public')->exists($this->about_image_secondary);
    }

    // Update metode yang ada
    /**
     * Get about section completion percentage
     */
    public function getAboutCompletionPercentage(): int
    {
        $criteria = [
            !empty($this->full_story),           // 35%
            $this->has_about_image,              // 20% 
            $this->has_about_image_secondary,    // 15%
            $this->highlights_count >= 3,        // 30%
        ];

        $weights = [35, 20, 15, 30];
        $totalWeight = 0;

        foreach ($criteria as $index => $completed) {
            if ($completed) {
                $totalWeight += $weights[$index];
            }
        }

        return $totalWeight;
    }

    /**
     * Check if business has complete about section
     */
    public function hasCompleteAboutSection(): bool
    {
        return !empty($this->full_story) &&
            $this->has_about_image &&
            $this->has_about_image_secondary &&
            $this->highlights_count >= 3;
    }

    /**
     * Get highlights for public website display
     */
    public function getPublicHighlights(int $limit = 6)
    {
        return $this->highlights()
            ->ordered()
            ->limit($limit)
            ->get()
            ->filter(function ($highlight) {
                return $highlight->isComplete();
            })
            ->map(function ($highlight) {
                return $highlight->getCardData();
            });
    }

    /**
     * Get business story summary (for public display)
     */
    public function getStoryExcerpt(int $wordLimit = 50): string
    {
        if (empty($this->full_story)) {
            return '';
        }

        // Strip HTML tags and get plain text
        $plainText = strip_tags($this->full_story);

        // Get excerpt
        $words = explode(' ', $plainText);
        if (count($words) <= $wordLimit) {
            return $plainText;
        }

        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }

    /**
     * Check if business about section is ready for public display
     */
    public function isAboutSectionPublicReady(): bool
    {
        return !empty($this->full_story) && $this->highlights_count >= 2;
    }

    // Update the completion calculation method to include about section

    /**
     * Calculate business completion including about section
     */
    public function calculateCompletionWithAbout(): int
    {
        $completion = $this->calculateCompletionWithTestimonials(); // Use existing method

        // Add about section completion
        $aboutCompletion = $this->getAboutCompletionPercentage();

        // About section contributes 15% to overall completion
        $aboutBonus = round($aboutCompletion * 0.15);

        return min(100, $completion + $aboutBonus);
    }

    // Add this to the SIMPLE COMPLETION CALCULATION section
    // Update the getSimpleCompletionStatusAttribute method to include about section:

    /**
     * Simple completion status including about section (updated)
     */
    public function getSimpleCompletionStatusAttribute(): array
    {
        $fields = [
            'basic_info' => [
                'label' => 'Informasi Dasar',
                'completed' => !empty($this->business_name) && !empty($this->main_address) && !empty($this->main_operational_hours),
                'weight' => 20
            ],
            'descriptions' => [
                'label' => 'Deskripsi',
                'completed' => !empty($this->short_description) && !empty($this->full_description),
                'weight' => 10
            ],
            'logo' => [
                'label' => 'Logo',
                'completed' => !empty($this->logo_url),
                'weight' => 10
            ],
            'location' => [
                'label' => 'Lokasi',
                'completed' => !empty($this->google_maps_link),
                'weight' => 10
            ],
            'products' => [
                'label' => 'Produk',
                'completed' => $this->products()->count() >= 3,
                'weight' => 15
            ],
            'galleries' => [
                'label' => 'Galeri',
                'completed' => $this->galleries()->count() >= 3,
                'weight' => 10
            ],
            'testimonials' => [
                'label' => 'Testimoni',
                'completed' => $this->testimonials()->count() >= 2,
                'weight' => 10
            ],
            'about_section' => [
                'label' => 'Tentang Bisnis',
                'completed' => !empty($this->full_story) && $this->highlights()->count() >= 3,
                'weight' => 15
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

    // Update the getSimpleNextSteps method to include about section:

    // Add scope for businesses with highlights

    /**
     * Scope for businesses with highlights
     */
    public function scopeWithHighlights($query)
    {
        return $query->has('highlights');
    }

    /**
     * Scope for businesses with sufficient highlights
     */
    public function scopeWithSufficientHighlights($query, int $minCount = 3)
    {
        return $query->whereHas('highlights', null, '>=', $minCount);
    }

    /**
     * Scope for businesses with complete about section
     */
    public function scopeWithCompleteAboutSection($query)
    {
        return $query->whereNotNull('full_story')
            ->whereNotNull('about_image')
            ->whereHas('highlights', null, '>=', 3);
    }

    // Add methods for about section data export

    /**
     * Get about section data for website generation
     */
    public function getAboutSectionData(): array
    {
        return [
            'story' => $this->full_story,
            'story_excerpt' => $this->getStoryExcerpt(),
            'about_image' => $this->about_image_url,
            'highlights' => $this->getPublicHighlights(),
            'stats' => $this->highlight_stats,
            'is_complete' => $this->hasCompleteAboutSection(),
            'completion_percentage' => $this->getAboutCompletionPercentage()
        ];
    }

    /**
     * Get complete business showcase data including about section
     */
    public function getCompleteBusinessShowcaseAttribute(): array
    {
        return [
            'business' => [
                'name' => $this->business_name,
                'description' => $this->short_description,
                'logo' => $this->logo_url,
                'hero_image' => $this->hero_image_url ?? $this->about_image_url,
                'url' => $this->public_url,
            ],
            'stats' => [
                'products_count' => $this->products_count,
                'galleries_count' => $this->galleries_count,
                'testimonials_count' => $this->testimonials_count,
                'highlights_count' => $this->highlights_count,
                'branches_count' => $this->branches_count ?? 0,
                'completion_rate' => $this->calculateCompletionWithAbout(),
            ],
            'content' => [
                'story' => $this->getAboutSectionData(),
                'featured_products' => $this->featured_products->take(6)->map(function ($product) {
                    return $product->getCardData();
                }),
                'galleries' => $this->latest_galleries->map(function ($gallery) {
                    return $gallery->getSimpleData();
                }),
                'testimonials' => $this->getPublicTestimonials(3),
                'highlights' => $this->getPublicHighlights(6),
            ],
            'media_summary' => $this->media_summary,
            'is_ready_to_publish' => $this->isReadyToPublish(),
        ];
    }

    /**
     * Update main completion calculation method
     */
    public function updateProgressCompletion(): int
    {
        $completion = $this->calculateCompletionWithAbout();
        $this->update(['progress_completion' => $completion]);

        return $completion;
    }
    // Add these relationships to the existing Business.php model (in RELATIONSHIPS section)

    /**
     * Get the template configuration for the business.
     */
    public function businessTemplate(): HasOne
    {
        return $this->hasOne(BusinessTemplate::class, 'business_id');
    }

    /**
     * Get the sections for the business.
     */
    public function businessSections(): HasMany
    {
        return $this->hasMany(BusinessSection::class, 'business_id');
    }

    // Add these methods in BUSINESS HELPER METHODS section

    /**
     * Get active template
     */
    public function getActiveTemplate()
    {
        return $this->businessTemplate ? $this->businessTemplate->template : null;
    }

    /**
     * Check if business has template configured
     */
    public function hasTemplate(): bool
    {
        return $this->businessTemplate !== null;
    }

    /**
     * Get active sections
     */
    public function getActiveSections(): array
    {
        return $this->businessSections()->where('is_active', true)->pluck('section')->toArray();
    }

    /**
     * Check if specific section is active
     */
    public function isSectionActive(string $section): bool
    {
        return $this->businessSections()
            ->where('section', $section)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get color palette
     */
    public function getColorPalette(): array
    {
        return $this->businessTemplate ? $this->businessTemplate->getColorPalette() : [
            'primary' => '#3B82F6',
            'secondary' => '#64748B',
            'accent' => '#F59E0B'
        ];
    }

    /**
     * Initialize default template and sections
     */
    public function initializeDefaultTemplate(): void
    {
        if (!$this->hasTemplate()) {
            // Get first active template
            $defaultTemplate = \App\Models\Template::where('is_active', true)->first();

            if ($defaultTemplate) {
                \App\Models\BusinessTemplate::create([
                    'business_id' => $this->id,
                    'template_id' => $defaultTemplate->id,
                    'color_palette' => [
                        'primary' => '#3B82F6',
                        'secondary' => '#64748B',
                        'accent' => '#F59E0B'
                    ]
                ]);
            }

            // Create default sections
            \App\Models\BusinessSection::createDefaultSections($this->id);
        }
    }

    /**
     * Get template completion status
     */
    public function getTemplateCompletionStatus(): array
    {
        $hasTemplate = $this->hasTemplate();
        $hasHeroImage = !empty($this->hero_image_url);
        $activeSectionsCount = $this->businessSections()->where('is_active', true)->count();

        return [
            'has_template' => $hasTemplate,
            'has_hero_image' => $hasHeroImage,
            'active_sections_count' => $activeSectionsCount,
            'is_complete' => $hasTemplate && $activeSectionsCount >= 3,
            'completion_percentage' => $this->calculateTemplateCompletion()
        ];
    }

    /**
     * Calculate template completion percentage
     */
    public function calculateTemplateCompletion(): int
    {
        $score = 0;

        if ($this->hasTemplate())
            $score += 40;
        if (!empty($this->hero_image_url))
            $score += 30;

        $activeSections = $this->businessSections()->where('is_active', true)->count();
        if ($activeSections >= 3)
            $score += 30;

        return $score;
    }

    /**
     * Update business completion including template
     */
    public function calculateCompletionWithTemplate(): int
    {
        $baseCompletion = $this->calculateCompletionWithAbout(); // Use existing method

        // Add template completion bonus (max 10%)
        $templateCompletion = $this->calculateTemplateCompletion();
        $templateBonus = round($templateCompletion * 0.1);

        return min(100, $baseCompletion + $templateBonus);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class, 'business_id');
    }

    /**
     * Get active contacts, ordered by display order
     */
    public function getActiveContactsAttribute()
    {
        return $this->contacts()
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get contacts of a specific type
     */
    public function getContactsByType($type)
    {
        return $this->contacts()
            ->active()
            ->where('contact_type', $type)
            ->ordered()
            ->get();
    }

    /**
     * Check if business has specific contact type
     */
    public function hasContactType($type): bool
    {
        return $this->contacts()
            ->active()
            ->where('contact_type', $type)
            ->exists();
    }
}
