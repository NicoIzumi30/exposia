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

    protected function casts(): array
    {
        return [
            'publish_status' => 'boolean',
            'progress_completion' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'business_id');
    }

    public function template(): HasOne
    {
        return $this->hasOne(BusinessTemplate::class);
    }

    public function businessTemplate(): HasOne
    {
        return $this->hasOne(BusinessTemplate::class, 'business_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(BusinessSection::class);
    }

    public function businessSections(): HasMany
    {
        return $this->hasMany(BusinessSection::class, 'business_id');
    }

    public function visitors(): HasMany
    {
        return $this->hasMany(BusinessVisitor::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'business_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'business_id');
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(BusinessHighlight::class, 'business_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class, 'business_id');
    }

    public function getBranchesCountAttribute(): int
    {
        return $this->branches()->count();
    }

    public function getBranchesWithContactAttribute()
    {
        return $this->branches()->withPhone()->get();
    }

    public function getBranchesWithMapsAttribute()
    {
        return $this->branches()->withMapsLink()->get();
    }

    public function getMainBranchAttribute(): ?Branch
    {
        return $this->branches()->oldest()->first();
    }

    public function getBranchStatsAttribute(): array
    {
        return get_branch_stats($this);
    }

    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    public function getPinnedProductsAttribute()
    {
        return $this->products()->pinned()->defaultOrder()->get();
    }

    public function getProductsWithImagesAttribute()
    {
        return $this->products()->withImages()->get();
    }

    public function getFeaturedProductsAttribute()
    {
        return $this->products()
            ->pinned()
            ->withImages()
            ->defaultOrder()
            ->limit(6)
            ->get();
    }

    public function getLatestProductsAttribute($limit = 6)
    {
        return $this->products()
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    public function getProductStatsAttribute(): array
    {
        return get_product_stats($this);
    }

    public function getAverageProductPriceAttribute(): float
    {
        return $this->products()->avg('product_price') ?: 0;
    }

    public function getProductPriceRangeAttribute(): array
    {
        $products = $this->products();

        return [
            'min' => $products->min('product_price') ?: 0,
            'max' => $products->max('product_price') ?: 0
        ];
    }

    public function getMostExpensiveProductAttribute(): ?Product
    {
        return $this->products()->orderBy('product_price', 'desc')->first();
    }

    public function getCheapestProductAttribute(): ?Product
    {
        return $this->products()->orderBy('product_price', 'asc')->first();
    }

    public function getGalleriesCountAttribute(): int
    {
        return $this->galleries()->count();
    }

    public function getLatestGalleriesAttribute($limit = 6)
    {
        return $this->galleries()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRandomGalleriesAttribute($limit = 4)
    {
        return $this->galleries()
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getGalleryStatsAttribute(): array
    {
        $count = $this->galleries_count;

        return [
            'total' => $count,
            'remaining' => max(0, 8 - $count),
            'can_upload' => $count < 8,
        ];
    }

    public function getMainGalleryImageAttribute(): ?Gallery
    {
        return $this->galleries()->latest()->first();
    }

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

    public function getGalleriesForSitemapAttribute()
    {
        return $this->galleries()
            ->latest()
            ->get(['id', 'gallery_image', 'updated_at']);
    }

    public function getTestimonialsCountAttribute(): int
    {
        return $this->testimonials()->count();
    }

    public function getLatestTestimonialsAttribute($limit = 5)
    {
        return $this->testimonials()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRandomTestimonialsAttribute($limit = 3)
    {
        return $this->testimonials()
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

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

    public function getHighlightsCountAttribute(): int
    {
        return $this->highlights()->count();
    }

    public function getLatestHighlightsAttribute($limit = 6)
    {
        return $this->highlights()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function getCompleteHighlightsAttribute()
    {
        return $this->highlights()
            ->get()
            ->filter(function ($highlight) {
                return $highlight->isComplete();
            });
    }

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

    public function getAboutImageUrlAttribute(): ?string
    {
        if (!$this->about_image) {
            return null;
        }

        return Storage::url($this->about_image);
    }

    public function getHasAboutImageAttribute(): bool
    {
        return !empty($this->about_image) && Storage::disk('public')->exists($this->about_image);
    }

    public function getAboutImageSecondaryUrlAttribute(): ?string
    {
        if (!$this->about_image_secondary) {
            return null;
        }

        return Storage::url($this->about_image_secondary);
    }

    public function getHasAboutImageSecondaryAttribute(): bool
    {
        return !empty($this->about_image_secondary) && Storage::disk('public')->exists($this->about_image_secondary);
    }

    public function getHeroImageUrlAttribute(): string
    {
       return $this->attributes['hero_image_url'] ?? "";
    }

    public function getAllMediaFilesAttribute(): array
    {
        $media = [];

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

    public function getActiveContactsAttribute()
    {
        return $this->contacts()
            ->active()
            ->ordered()
            ->get();
    }

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

    public function getLogoUrl()
    {
        return business_logo_url($this);
    }

    public function getFormattedAddress($maxLength = 100)
    {
        return format_business_address($this->main_address, $maxLength);
    }

    public function getStatusBadge()
    {
        return business_status_badge($this);
    }

    public function getStatusText()
    {
        return get_business_status_text($this);
    }

    public function getStatusColor()
    {
        return get_business_status_color($this);
    }

    public function getOperationalHoursArray()
    {
        return business_operational_hours_array($this->main_operational_hours);
    }

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

    public function generateQrCode($size = 200)
    {
        if (!$this->public_url) {
            return null;
        }

        $qrCodeUrl = generate_qr_code_url($this->public_url, $size);
        $this->update(['qr_code' => $qrCodeUrl]);

        return $qrCodeUrl;
    }

    public function getFullPublicUrl()
    {
        if (!$this->public_url) {
            return null;
        }

        if (filter_var($this->public_url, FILTER_VALIDATE_URL)) {
            return $this->public_url;
        }

        return url($this->public_url);
    }

    public function getSlug()
    {
        if (!$this->public_url) {
            return null;
        }

        return basename(parse_url($this->public_url, PHP_URL_PATH));
    }

    public function hasProducts(): bool
    {
        return $this->products_count > 0;
    }

    public function hasCompleteProductInfo(): bool
    {
        if ($this->products_count === 0) {
            return false;
        }

        return $this->products->every(function ($product) {
            return $product->isComplete();
        });
    }

    public function hasGalleries(): bool
    {
        return $this->galleries_count > 0;
    }

    public function hasSufficientMediaContent(): bool
    {
        $productImages = $this->products()->whereNotNull('product_image')->count();
        $galleryImages = $this->galleries_count;

        return ($productImages >= 3) || ($galleryImages >= 3) || (($productImages + $galleryImages) >= 5);
    }

    public function hasTestimonials(): bool
    {
        return $this->testimonials_count > 0;
    }

    public function hasCompleteTestimonialInfo(): bool
    {
        if ($this->testimonials_count === 0) {
            return false;
        }

        return $this->testimonials->every(function ($testimonial) {
            return $testimonial->isComplete();
        });
    }

    public function hasHighlights(): bool
    {
        return $this->highlights_count > 0;
    }

    public function hasSufficientHighlights(int $minCount = 3): bool
    {
        return $this->highlights_count >= $minCount;
    }

    public function hasCompleteAboutSection(): bool
    {
        return !empty($this->full_story) &&
            $this->has_about_image &&
            $this->has_about_image_secondary &&
            $this->highlights_count >= 3;
    }

    public function hasCompleteBranchInfo(): bool
    {
        if ($this->branches_count === 0) {
            return false;
        }

        return $this->branches->every(function ($branch) {
            return $branch->isComplete();
        });
    }

    public function hasTemplate(): bool
    {
        return $this->businessTemplate !== null;
    }

    public function hasContactType($type): bool
    {
        return $this->contacts()
            ->active()
            ->where('contact_type', $type)
            ->exists();
    }

    public function canAddMoreProducts(): bool
    {
        $maxProducts = 100;
        return $this->products_count < $maxProducts;
    }

    public function canAddMoreGalleries(): bool
    {
        return $this->galleries_count < 8;
    }

    public function isPublished()
    {
        return $this->publish_status === true;
    }

    public function isDraft()
    {
        return $this->publish_status === false;
    }

    public function publish()
    {
        return $this->update(['publish_status' => true]);
    }

    public function unpublish()
    {
        return $this->update(['publish_status' => false]);
    }

    public function isReadyToPublish()
    {
        return $this->progress_completion >= 80;
    }

    public function isAboutSectionPublicReady(): bool
    {
        return !empty($this->full_story) && $this->highlights_count >= 2;
    }

    public function isSectionActive(string $section): bool
    {
        return $this->businessSections()
            ->where('section', $section)
            ->where('is_active', true)
            ->exists();
    }

    public function calculateCompletionWithBranches(): int
    {
        $baseCompletion = business_completion($this);

        if ($this->branches_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 5);
        }

        return $baseCompletion;
    }

    public function calculateCompletionWithProducts(): int
    {
        $baseCompletion = business_completion($this);

        if ($this->products_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 5);

            $productCompletionRate = $this->getProductCompletionRate();
            $productBonus = round($productCompletionRate * 0.1);
            $baseCompletion = min(100, $baseCompletion + $productBonus);
        }

        return $baseCompletion;
    }

    public function calculateCompletionWithGalleries(): int
    {
        $baseCompletion = business_completion($this);

        if ($this->galleries_count > 0) {
            $baseCompletion = min(100, $baseCompletion + 10);
        }

        return $baseCompletion;
    }

    public function calculateCompletionWithTestimonials(): int
    {
        $completion = $this->calculateCompletionWithGalleries();

        if ($this->testimonials_count > 0) {
            $completion = min(100, $completion + 5);

            if ($this->testimonials_count >= 3) {
                $completion = min(100, $completion + 5);
            }
        }

        return $completion;
    }

    public function calculateCompletionWithAbout(): int
    {
        $completion = $this->calculateCompletionWithTestimonials();

        $aboutCompletion = $this->getAboutCompletionPercentage();
        $aboutBonus = round($aboutCompletion * 0.15);

        return min(100, $completion + $aboutBonus);
    }

    public function calculateCompletionWithTemplate(): int
    {
        $baseCompletion = $this->calculateCompletionWithAbout();

        $templateCompletion = $this->calculateTemplateCompletion();
        $templateBonus = round($templateCompletion * 0.1);

        return min(100, $baseCompletion + $templateBonus);
    }

    public function updateCompletionWithMedia(): int
    {
        $completion = $this->calculateCompletionWithGalleries();
        $this->update(['progress_completion' => $completion]);

        return $completion;
    }

    public function updateProgressCompletion(): int
    {
        $completion = $this->calculateCompletionWithAbout();
        $this->update(['progress_completion' => $completion]);

        return $completion;
    }

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

    public function getAboutCompletionPercentage(): int
    {
        $criteria = [
            !empty($this->full_story),
            $this->has_about_image,
            $this->has_about_image_secondary,
            $this->highlights_count >= 3,
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

    public function searchProducts(string $query, int $limit = 10)
    {
        return $this->products()
            ->search($query)
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    public function getProductsByPriceRange($minPrice = null, $maxPrice = null, $limit = 20)
    {
        return $this->products()
            ->priceRange($minPrice, $maxPrice)
            ->defaultOrder()
            ->limit($limit)
            ->get();
    }

    public function getRandomProducts(int $count = 4)
    {
        return $this->products()
            ->withImages()
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    public function getProductsForSitemap()
    {
        return $this->products()
            ->whereNotNull('product_image')
            ->defaultOrder()
            ->get(['id', 'product_name', 'updated_at']);
    }

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

    public function getStoryExcerpt(int $wordLimit = 50): string
    {
        if (empty($this->full_story)) {
            return '';
        }

        $plainText = strip_tags($this->full_story);
        $words = explode(' ', $plainText);
        
        if (count($words) <= $wordLimit) {
            return $plainText;
        }

        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }

    public function getContactsByType($type)
    {
        return $this->contacts()
            ->active()
            ->where('contact_type', $type)
            ->ordered()
            ->get();
    }

    public function getActiveTemplate()
    {
        return $this->businessTemplate ? $this->businessTemplate->template : null;
    }

    public function getActiveSections(): array
    {
        return $this->businessSections()->where('is_active', true)->pluck('section')->toArray();
    }

    public function getColorPalette(): array
    {
        return $this->businessTemplate ? $this->businessTemplate->getColorPalette() : [
            'primary' => '#3B82F6',
            'secondary' => '#64748B',
            'accent' => '#F59E0B'
        ];
    }

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

        return array_slice($nextSteps, 0, 3);
    }

    public function initializeDefaultTemplate(): void
    {
        if (!$this->hasTemplate()) {
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

            \App\Models\BusinessSection::createDefaultSections($this->id);
        }
    }

    public function updateSeoMeta()
    {
        $seoTitle = $this->business_name;
        $seoDescription = $this->short_description;
        $seoKeywords = implode(', ', [
            $this->business_name,
            'bisnis',
            'usaha',
        ]);
    }

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

    public function scopePublished($query)
    {
        return $query->where('publish_status', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('publish_status', false);
    }

    public function scopeWithMinCompletion($query, $percentage = 50)
    {
        return $query->where('progress_completion', '>=', $percentage);
    }

    public function scopeWithGalleries($query)
    {
        return $query->has('galleries');
    }

    public function scopeWithSufficientMediaContent($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('products', function ($productQuery) {
                $productQuery->whereNotNull('product_image');
            }, '>=', 3)
                ->orWhereHas('galleries', null, '>=', 3);
        });
    }

    public function scopeWithTestimonials($query)
    {
        return $query->has('testimonials');
    }

    public function scopeWithSufficientTestimonials($query, int $minCount = 2)
    {
        return $query->whereHas('testimonials', null, '>=', $minCount);
    }

    public function scopeWithHighlights($query)
    {
        return $query->has('highlights');
    }

    public function scopeWithSufficientHighlights($query, int $minCount = 3)
    {
        return $query->whereHas('highlights', null, '>=', $minCount);
    }

    public function scopeWithCompleteAboutSection($query)
    {
        return $query->whereNotNull('full_story')
            ->whereNotNull('about_image')
            ->whereHas('highlights', null, '>=', 3);
    }
}