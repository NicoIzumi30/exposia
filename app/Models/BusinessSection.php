<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class BusinessSection extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'section',
        'is_active',
        'style_variant'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Available sections with their style variants
    public static $availableSections = [
        'navbar' => [
            'name' => 'Navigation Bar',
            'variants' => [
                'A' => 'Navbar Minimalis',
                'B' => 'Navbar dengan Logo Besar',
                'C' => 'Navbar dengan Menu Dropdown'
            ]
        ],
        'hero' => [
            'name' => 'Hero Section',
            'variants' => [
                'A' => 'Hero dengan Background Image',
                'B' => 'Hero dengan Video Background',
                'C' => 'Hero Split Layout'
            ]
        ],
        'about' => [
            'name' => 'Tentang Usaha',
            'variants' => [
                'A' => 'About dengan Gambar Kiri',
                'B' => 'About dengan Gambar Kanan',
                'C' => 'About Center Layout'
            ]
        ],
        'branches' => [
            'name' => 'Cabang',
            'variants' => [
                'A' => 'List View dengan Maps',
                'B' => 'Card Grid Layout',
                'C' => 'Tabs per Cabang'
            ]
        ],
        'produk' => [
            'name' => 'Produk',
            'variants' => [
                'A' => 'Grid 3 Kolom',
                'B' => 'Carousel Slider',
                'C' => 'Masonry Layout'
            ]
        ],
        'galeri' => [
            'name' => 'Galeri',
            'variants' => [
                'A' => 'Grid Gallery',
                'B' => 'Lightbox Gallery',
                'C' => 'Slider Gallery'
            ]
        ],
        'testimoni' => [
            'name' => 'Testimoni',
            'variants' => [
                'A' => 'Card Testimonials',
                'B' => 'Slider Testimonials',
                'C' => 'Quote Style'
            ]
        ],
        'kontak' => [
            'name' => 'Kontak',
            'variants' => [
                'A' => 'Kontak Minimalis',
                'B' => 'Kontak dengan Kolom',
                'C' => 'Kontak dengan Social Media'
            ]
        ],
        'footer' => [
            'name' => 'Footer',
            'variants' => [
                'A' => 'Footer Minimalis',
            ]
        ],
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    // Helper methods
    public function getSectionName()
    {
        return self::$availableSections[$this->section]['name'] ?? $this->section;
    }

    public function getStyleVariantName()
    {
        $variants = self::$availableSections[$this->section]['variants'] ?? [];
        return $variants[$this->style_variant] ?? 'Default';
    }

    public function getFullDisplayName()
    {
        return $this->getSectionName() . ' - ' . $this->getStyleVariantName();
    }

    public static function getAvailableSections()
    {
        return self::$availableSections;
    }

    public static function getSectionVariants($section)
    {
        return self::$availableSections[$section]['variants'] ?? [];
    }

    public static function createDefaultSections($businessId)
    {
        foreach (self::$availableSections as $section => $config) {
            $defaultVariant = array_key_first($config['variants']); // First variant as default

            self::updateOrCreate(
                ['business_id' => $businessId, 'section' => $section],
                [
                    'is_active' => true,
                    'style_variant' => $defaultVariant
                ]
            );
        }
    }

    public static function toggleSection($businessId, $section)
    {
        $businessSection = self::where('business_id', $businessId)
            ->where('section', $section)
            ->first();

        if ($businessSection) {
            $businessSection->is_active = !$businessSection->is_active;
            $businessSection->save();
        }

        return $businessSection;
    }

    public static function updateSectionStyle($businessId, $section, $styleVariant)
    {
        $businessSection = self::where('business_id', $businessId)
            ->where('section', $section)
            ->first();

        if ($businessSection) {
            $businessSection->style_variant = $styleVariant;
            $businessSection->save();
        } else {
            // Create if doesn't exist
            $businessSection = self::create([
                'business_id' => $businessId,
                'section' => $section,
                'style_variant' => $styleVariant,
                'is_active' => true
            ]);
        }

        return $businessSection;
    }
}
