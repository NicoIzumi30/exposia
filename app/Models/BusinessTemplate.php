<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessTemplate extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'template_id',
        'color_palette',
        'hero_section',
        'about_section',
        'products_section',
        'gallery_section',
        'testimonial_section',
    ];

    protected $casts = [
        'color_palette' => 'json',
        'hero_section' => 'json',
        'about_section' => 'json',
        'products_section' => 'json',
        'gallery_section' => 'json',
        'testimonial_section' => 'json',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}