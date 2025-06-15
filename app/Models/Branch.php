<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'branch_name',
        'branch_address',
        'branch_operational_hours',
        'branch_google_maps_link',
        'branch_phone',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}