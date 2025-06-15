<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'testimonial_name',
        'testimonial_content',
        'testimonial_position',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}