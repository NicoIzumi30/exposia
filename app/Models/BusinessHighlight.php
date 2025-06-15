<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHighlight extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'icon',
        'title',
        'description',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}