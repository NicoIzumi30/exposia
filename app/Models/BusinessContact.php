<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;

class BusinessContact extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    protected $fillable = [
        'business_id',
        'contact_type',
        'contact_title',
        'contact_description',
        'contact_value',
        'contact_icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Jenis kontak yang tersedia dengan pengaturan default
     */
    public static $availableTypes = [
        'instagram' => [
            'name' => 'Instagram',
            'icon' => 'fab fa-instagram',
            'title' => 'Kunjungi Instagram Kami',
            'description' => 'Lihat produk secara real',
            'prefix' => 'https://instagram.com/'
        ],
        'shopee' => [
            'name' => 'Shopee',
            'icon' => 'fas fa-shopping-bag',
            'title' => 'Kunjungi Shopee',
            'description' => 'Lihat produk lain',
            'prefix' => 'https://shopee.co.id/'
        ],
        'tokopedia' => [
            'name' => 'Tokopedia',
            'icon' => 'fas fa-store',
            'title' => 'Kunjungi Tokopedia',
            'description' => 'Lihat produk lain',
            'prefix' => 'https://tokopedia.com/toko/'
        ],
        'maps' => [
            'name' => 'Google Maps',
            'icon' => 'fas fa-map-marker-alt',
            'title' => 'Kunjungi Toko Kami',
            'description' => 'Lihat produk secara real',
            'prefix' => ''
        ],
        'whatsapp' => [
            'name' => 'WhatsApp',
            'icon' => 'fab fa-whatsapp',
            'title' => 'Chat Customer Service',
            'description' => 'Lihat produk secara real',
            'prefix' => 'https://wa.me/'
        ],
        'website' => [
            'name' => 'Website',
            'icon' => 'fas fa-globe',
            'title' => 'Kunjungi Website',
            'description' => 'Lihat informasi lengkap',
            'prefix' => ''
        ],
        'facebook' => [
            'name' => 'Facebook',
            'icon' => 'fab fa-facebook',
            'title' => 'Kunjungi Facebook Kami',
            'description' => 'Lihat update terbaru',
            'prefix' => 'https://facebook.com/'
        ],
        'tiktok' => [
            'name' => 'TikTok',
            'icon' => 'fab fa-tiktok',
            'title' => 'Kunjungi TikTok Kami',
            'description' => 'Lihat video terbaru',
            'prefix' => 'https://tiktok.com/@'
        ],
        'custom' => [
            'name' => 'Custom',
            'icon' => 'fas fa-link',
            'title' => 'Link Kustom',
            'description' => 'Kunjungi kami',
            'prefix' => ''
        ],
    ];

    /**
     * Relasi ke business
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Get URL kontak lengkap
     */
    public function getContactUrlAttribute(): string
    {
        $type = $this->contact_type;
        $value = $this->contact_value;

        // Khusus untuk WhatsApp - bersihkan nomor
        if ($type === 'whatsapp') {
            $value = preg_replace('/[^0-9]/', '', $value);
        }

        // Ambil prefix untuk jenis kontak ini
        $prefix = self::$availableTypes[$type]['prefix'] ?? '';

        return $prefix . $value;
    }

    /**
     * Get HTML ikon
     */
    public function getIconHtmlAttribute(): string
    {
        $icon = $this->contact_icon ?: (self::$availableTypes[$this->contact_type]['icon'] ?? 'fas fa-link');
        return '<i class="' . $icon . '"></i>';
    }

    /**
     * Get data untuk tampilan
     */
    public function getDisplayDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->contact_type,
            'title' => $this->contact_title,
            'description' => $this->contact_description,
            'value' => $this->contact_value,
            'url' => $this->contact_url,
            'icon' => $this->icon_html,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];
    }

    /**
     * Scope: Filter kontak aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Urutkan berdasarkan display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get jenis kontak yang tersedia
     */
    public static function getAvailableTypes(): array
    {
        return self::$availableTypes;
    }
}