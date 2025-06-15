<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'preview_url', 
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function businessTemplates(): HasMany
    {
        return $this->hasMany(BusinessTemplate::class);
    }
}