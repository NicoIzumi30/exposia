<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Template extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'preview_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function businessTemplates()
    {
        return $this->hasMany(BusinessTemplate::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function getThumbnailUrl()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/template-placeholder.jpg');
    }

    public function getPreviewLink()
    {
        return $this->preview_url ?: '#';
    }

    public function isUsed()
    {
        return $this->businessTemplates()->count() > 0;
    }
}