<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessVisitor extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}