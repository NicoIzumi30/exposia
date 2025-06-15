<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'product_name',
        'product_image',
        'product_description',
        'product_price',
        'product_wa_link',
        'is_pinned',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'is_pinned' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}