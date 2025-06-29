<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

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
        'testimonial_section'
    ];

    protected $casts = [
        'color_palette' => 'array',
        'hero_section' => 'array',
        'about_section' => 'array',
        'products_section' => 'array',
        'gallery_section' => 'array',
        'testimonial_section' => 'array'
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    // Helper methods
    public function getPrimaryColor()
    {
        return $this->color_palette['primary'] ?? '#EAEFEF';
    }

    public function getSecondaryColor()
    {
        return $this->color_palette['secondary'] ?? '#D9D9D9';
    }

    public function getAccentColor()
    {
        return $this->color_palette['accent'] ?? '#012C4E';
    }
    public function getHighlightColor()
    {
        $palette = $this->color_palette;
        return isset($palette['highlight']) ? $palette['highlight'] : '#FFFFFF'; // Default: Purple
    }
    public function getColorPalette(): array
    {
        $defaultPalette = [
            'accent' => '#012C4E',
            'primary' => '#EAEFEF',
            'secondary' => '#D9D9D9',
            'highlight' => '#FFFFFF' // Warna keempat: highlight
        ];

        if (empty($this->color_palette)) {
            return $defaultPalette;
        }

        return array_merge($defaultPalette, $this->color_palette);
    }

    public function getSectionConfig($section)
    {
        return $this->{$section . '_section'} ?? [];
    }

    public function updateSectionConfig($section, $config)
    {
        $this->{$section . '_section'} = array_merge($this->getSectionConfig($section), $config);
        $this->save();
    }
}
