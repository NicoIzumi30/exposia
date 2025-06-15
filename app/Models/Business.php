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
    protected $casts = [
        'publish_status' => 'boolean',
        'progress_completion' => 'integer',
    ];

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
        return $this->hasMany(Branch::class);
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
}