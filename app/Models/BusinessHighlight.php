<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;

class BusinessHighlight extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    /**
     * The table associated with the model.
     */
    protected $table = 'business_highlights';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'business_id',
        'icon',
        'title',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'short_description',
        'icon_html'
    ];

    /**
     * Relationship: BusinessHighlight belongs to Business
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Accessor: Get short description for display
     */
    public function getShortDescriptionAttribute(): string
    {
        return \Illuminate\Support\Str::limit($this->description, 100);
    }

    /**
     * Accessor: Get icon HTML with Font Awesome classes
     */
    public function getIconHtmlAttribute(): string
    {
        // Ensure icon has Font Awesome classes
        $icon = $this->icon;
        if (!str_starts_with($icon, 'fa')) {
            $icon = 'fas fa-' . $icon;
        }
        
        return '<i class="' . $icon . '"></i>';
    }

    /**
     * Accessor: Get display data for cards
     */
    public function getCardDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'icon_html' => $this->icon_html,
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->short_description
        ];
    }

    /**
     * Scope: Filter highlights by business
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    /**
     * Scope: Order by creation date
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Check if highlight is complete
     */
    public function isComplete(): bool
    {
        return !empty($this->icon) &&
               !empty($this->title) &&
               !empty($this->description);
    }

    /**
     * Get available Font Awesome icons for highlights
     */
    public static function getAvailableIcons(): array
    {
        return [
            'fas fa-star' => 'Bintang',
            'fas fa-heart' => 'Hati',
            'fas fa-thumbs-up' => 'Thumbs Up',
            'fas fa-trophy' => 'Trophy',
            'fas fa-medal' => 'Medal',
            'fas fa-crown' => 'Crown',
            'fas fa-gem' => 'Gem',
            'fas fa-fire' => 'Fire',
            'fas fa-bolt' => 'Lightning',
            'fas fa-magic' => 'Magic',
            'fas fa-rocket' => 'Rocket',
            'fas fa-shield-alt' => 'Shield',
            'fas fa-check-circle' => 'Check Circle',
            'fas fa-clock' => 'Clock',
            'fas fa-map-marker-alt' => 'Location',
            'fas fa-phone' => 'Phone',
            'fas fa-envelope' => 'Email',
            'fas fa-globe' => 'Globe',
            'fas fa-users' => 'Users',
            'fas fa-handshake' => 'Handshake',
            'fas fa-tools' => 'Tools',
            'fas fa-cogs' => 'Settings',
            'fas fa-lightbulb' => 'Lightbulb',
            'fas fa-leaf' => 'Leaf',
            'fas fa-recycle' => 'Recycle',
            'fas fa-balance-scale' => 'Balance',
            'fas fa-dollar-sign' => 'Dollar',
            'fas fa-chart-line' => 'Chart',
            'fas fa-graduation-cap' => 'Education',
            'fas fa-certificate' => 'Certificate',
            'fas fa-award' => 'Award',
            'fas fa-gift' => 'Gift',
            'fas fa-smile' => 'Smile',
            'fas fa-coffee' => 'Coffee',
            'fas fa-home' => 'Home',
            'fas fa-building' => 'Building',
            'fas fa-store' => 'Store',
            'fas fa-shipping-fast' => 'Fast Shipping',
            'fas fa-truck' => 'Truck',
            'fas fa-credit-card' => 'Credit Card',
            'fas fa-lock' => 'Security',
            'fas fa-wifi' => 'WiFi',
            'fas fa-mobile-alt' => 'Mobile',
            'fas fa-laptop' => 'Laptop'
        ];
    }

    /**
     * Get icon name without Font Awesome prefix
     */
    public function getIconNameAttribute(): string
    {
        return str_replace(['fas fa-', 'far fa-', 'fab fa-'], '', $this->icon);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID when creating
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });

        // Log activity when highlight is created
        static::created(function ($highlight) {
            log_activity('Business highlight ditambahkan: ' . $highlight->title, $highlight);
        });

        // Log activity when highlight is updated
        static::updated(function ($highlight) {
            log_activity('Business highlight diperbarui: ' . $highlight->title, $highlight);
        });

        // Log activity when highlight is deleted
        static::deleted(function ($highlight) {
            log_activity('Business highlight dihapus: ' . $highlight->title);
        });
    }
}