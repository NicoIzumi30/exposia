<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    /**
     * The table associated with the model.
     */
    protected $table = 'products';

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
        'product_name',
        'product_image',
        'product_description',
        'product_price',
        'product_wa_link',
        'is_pinned',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_pinned' => 'boolean',
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
        'formatted_price',
        'image_url',
        'whatsapp_order_link',
        'slug',
        'share_url'
    ];

    /**
     * Relationship: Product belongs to Business
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Accessor: Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return format_currency($this->product_price);
    }

    /**
     * Accessor: Get full image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->product_image) {
            return null;
        }

        return Storage::url($this->product_image);
    }

    /**
     * Accessor: Get WhatsApp order link
     */
    public function getWhatsappOrderLinkAttribute(): ?string
    {
        $phone = $this->product_wa_link ?: $this->business?->main_phone;
        
        if (!$phone) {
            return null;
        }

        $message = "Halo! Saya tertarik dengan produk:\n\n";
        $message .= "*{$this->product_name}*\n";
        $message .= "Harga: {$this->formatted_price}\n\n";
        $message .= "Mohon informasi lebih lanjut. Terima kasih!";

        return whatsapp_link($phone, $message);
    }

    /**
     * Accessor: Get product slug for URL
     */
    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->product_name . '-' . substr($this->id, 0, 8));
    }

    /**
     * Accessor: Get share URL for product
     */
    public function getShareUrlAttribute(): string
    {
        return url('/product/' . $this->slug);
    }

    /**
     * Accessor: Get short description for preview
     */
    public function getShortDescriptionAttribute(): string
    {
        return \Illuminate\Support\Str::limit($this->product_description, 100);
    }

    /**
     * Accessor: Check if product has image
     */
    public function getHasImageAttribute(): bool
    {
        return !empty($this->product_image) && Storage::disk('public')->exists($this->product_image);
    }

    /**
     * Accessor: Get product status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_pinned) {
            return '<span class="bg-red-500 text-white px-2 py-1 rounded text-xs">Unggulan</span>';
        }

        return '<span class="bg-gray-500 text-white px-2 py-1 rounded text-xs">Normal</span>';
    }

    /**
     * Scope: Filter pinned products
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope: Filter unpinned products
     */
    public function scopeUnpinned($query)
    {
        return $query->where('is_pinned', false);
    }

    /**
     * Scope: Filter products by business
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    /**
     * Scope: Filter products with images
     */
    public function scopeWithImages($query)
    {
        return $query->whereNotNull('product_image');
    }

    /**
     * Scope: Filter products with WhatsApp links
     */
    public function scopeWithWhatsApp($query)
    {
        return $query->whereNotNull('product_wa_link');
    }

    /**
     * Scope: Search products by name or description
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', "%{$search}%")
              ->orWhere('product_description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter products by price range
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('product_price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('product_price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope: Order by pinned first, then by newest
     */
    public function scopeDefaultOrder($query)
    {
        return $query->orderBy('is_pinned', 'desc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Check if product is complete (has all recommended fields)
     */
    public function isComplete(): bool
    {
        return !empty($this->product_name) &&
               !empty($this->product_description) &&
               !empty($this->product_price) &&
               !empty($this->product_image) &&
               !empty($this->product_wa_link);
    }

    /**
     * Get completion percentage for this product
     */
    public function getCompletionPercentage(): int
    {
        $totalFields = 5; // name, description, price, image, wa_link
        $completedFields = 3; // name, description, price are required

        if (!empty($this->product_image)) $completedFields++;
        if (!empty($this->product_wa_link)) $completedFields++;

        return round(($completedFields / $totalFields) * 100);
    }

    /**
     * Generate product card data for display
     */
    public function getCardData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->product_name,
            'description' => $this->short_description,
            'price' => $this->formatted_price,
            'image' => $this->image_url,
            'is_pinned' => $this->is_pinned,
            'whatsapp_link' => $this->whatsapp_order_link,
            'share_url' => $this->share_url,
            'completion' => $this->getCompletionPercentage()
        ];
    }

    /**
     * Generate SEO meta data for product
     */
    public function getSeoData(): array
    {
        $businessName = $this->business?->business_name ?? 'UMKM';
        
        return [
            'title' => $this->product_name . ' - ' . $businessName,
            'description' => $this->short_description,
            'keywords' => $this->generateKeywords(),
            'og_title' => $this->product_name,
            'og_description' => $this->short_description,
            'og_image' => $this->image_url,
            'og_url' => $this->share_url
        ];
    }

    /**
     * Generate keywords for SEO
     */
    public function generateKeywords(): string
    {
        $keywords = [];
        
        // Add product name words
        $nameWords = explode(' ', $this->product_name);
        $keywords = array_merge($keywords, $nameWords);
        
        // Add business name if available
        if ($this->business && $this->business->business_name) {
            $businessWords = explode(' ', $this->business->business_name);
            $keywords = array_merge($keywords, $businessWords);
        }
        
        // Add generic keywords
        $keywords = array_merge($keywords, ['produk', 'jual', 'beli', 'online', 'murah', 'berkualitas']);
        
        // Clean and unique
        $keywords = array_unique(array_filter($keywords, function($word) {
            return strlen($word) > 2;
        }));
        
        return implode(', ', $keywords);
    }

    /**
     * Generate Schema.org structured data
     */
    public function getStructuredData(): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->product_name,
            'description' => $this->product_description,
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->product_price,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock'
            ]
        ];

        if ($this->image_url) {
            $data['image'] = $this->image_url;
        }

        if ($this->business) {
            $data['brand'] = [
                '@type' => 'Brand',
                'name' => $this->business->business_name
            ];
        }

        return $data;
    }

    /**
     * Create product duplicate
     */
    public function duplicate(): self
    {
        $duplicate = $this->replicate();
        $duplicate->product_name = $this->product_name . ' (Copy)';
        $duplicate->is_pinned = false;
        $duplicate->save();

        // Copy image if exists
        if ($this->product_image && Storage::disk('public')->exists($this->product_image)) {
            $originalPath = $this->product_image;
            $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
            $newPath = 'product-images/' . \Illuminate\Support\Str::uuid() . '.' . $extension;
            
            Storage::disk('public')->copy($originalPath, $newPath);
            $duplicate->update(['product_image' => $newPath]);
        }

        return $duplicate;
    }

    /**
     * Get related products (from same business)
     */
    public function getRelatedProducts(int $limit = 4)
    {
        return $this->business->products()
            ->where('id', '!=', $this->id)
            ->defaultOrder()
            ->limit($limit)
            ->get();
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

        // Log activity when product is created
        static::created(function ($product) {
            log_activity('Produk baru dibuat: ' . $product->product_name, $product);
        });

        // Log activity when product is updated
        static::updated(function ($product) {
            log_activity('Produk diperbarui: ' . $product->product_name, $product);
        });

        // Log activity when product is deleted
        static::deleted(function ($product) {
            // Delete image file if exists
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }
            
            log_activity('Produk dihapus: ' . $product->product_name);
        });
    }
}