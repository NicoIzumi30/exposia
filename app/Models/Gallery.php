<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'gallery_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the business that owns the gallery image.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    // ========================================
    // ACCESSORS & ATTRIBUTES
    // ========================================

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->gallery_image) {
            return asset('images/placeholder-gallery.jpg');
        }

        if (filter_var($this->gallery_image, FILTER_VALIDATE_URL)) {
            return $this->gallery_image;
        }

        return Storage::url($this->gallery_image);
    }

    /**
     * Get image file size
     */
    public function getFileSizeAttribute(): int
    {
        if (!$this->gallery_image || !Storage::disk('public')->exists($this->gallery_image)) {
            return 0;
        }

        return Storage::disk('public')->size($this->gallery_image);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        return format_file_size($this->file_size);
    }

    /**
     * Get image dimensions
     */
    public function getImageDimensionsAttribute(): array
    {
        if (!$this->gallery_image || !Storage::disk('public')->exists($this->gallery_image)) {
            return ['width' => 0, 'height' => 0];
        }

        $imagePath = Storage::disk('public')->path($this->gallery_image);
        
        try {
            $imageInfo = getimagesize($imagePath);
            return [
                'width' => $imageInfo[0] ?? 0,
                'height' => $imageInfo[1] ?? 0,
            ];
        } catch (\Exception $e) {
            return ['width' => 0, 'height' => 0];
        }
    }

    /**
     * Get simple display name
     */
    public function getDisplayNameAttribute(): string
    {
        return 'Foto Galeri - ' . $this->created_at->format('d M Y');
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Generate simple image data for API/JSON responses
     */
    public function getSimpleData(): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'file_size' => $this->formatted_file_size,
            'dimensions' => $this->image_dimensions,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at->format('d M Y H:i'),
        ];
    }

    /**
     * Delete image file when model is deleted
     */
    public function deleteImageFile(): bool
    {
        if ($this->gallery_image && Storage::disk('public')->exists($this->gallery_image)) {
            return Storage::disk('public')->delete($this->gallery_image);
        }

        return true;
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope for recent images (newest first)
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ========================================
    // STATIC HELPER METHODS
    // ========================================

    /**
     * Get simple gallery statistics for a business
     */
    public static function getSimpleStatsForBusiness($businessId): array
    {
        $count = static::where('business_id', $businessId)->count();

        return [
            'total' => $count,
            'remaining' => max(0, 8 - $count), // Max 8 images
            'can_upload' => $count < 8,
        ];
    }

    /**
     * Cleanup orphaned gallery files
     */
    public static function cleanupOrphanedFiles(): array
    {
        try {
            $allFiles = Storage::disk('public')->allFiles('gallery-images');
            $usedFiles = static::whereNotNull('gallery_image')->pluck('gallery_image')->toArray();
            
            $orphanedFiles = array_diff($allFiles, $usedFiles);
            $cleanedCount = 0;

            foreach ($orphanedFiles as $file) {
                if (Storage::disk('public')->delete($file)) {
                    $cleanedCount++;
                }
            }

            return [
                'success' => true,
                'cleaned' => $cleanedCount,
                'message' => "Cleaned {$cleanedCount} orphaned gallery files"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'cleaned' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // ========================================
    // MODEL EVENTS
    // ========================================

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Log creation
        static::created(function ($gallery) {
            log_activity('Gallery image uploaded: ' . $gallery->display_name, $gallery);
        });

        // Delete image file and log when model is deleted
        static::deleted(function ($gallery) {
            $gallery->deleteImageFile();
            log_activity('Gallery image deleted: ' . $gallery->display_name);
        });
    }
}