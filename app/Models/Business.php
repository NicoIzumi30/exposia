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
     * Get the products for the business.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the galleries for the business.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
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
}   