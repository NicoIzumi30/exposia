<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;

class Testimonial extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    /**
     * The table associated with the model.
     */
    protected $table = 'testimonials';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'business_id',
        'testimonial_name',
        'testimonial_content',
        'testimonial_position',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'short_content',
        'formatted_date'
    ];

    /**
     * Relationship: Testimonial belongs to Business
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Accessor: Get short content for table display
     */
    public function getShortContentAttribute(): string
    {
        return \Illuminate\Support\Str::limit($this->testimonial_content, 100);
    }

    /**
     * Accessor: Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Accessor: Get display name with position
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->testimonial_name;
        if ($this->testimonial_position) {
            $name .= ' - ' . $this->testimonial_position;
        }
        return $name;
    }

    /**
     * Scope: Filter testimonials by business
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    /**
     * Scope: Order by newest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Check if testimonial is complete
     */
    public function isComplete(): bool
    {
        return !empty($this->testimonial_name) &&
               !empty($this->testimonial_content);
    }

    /**
     * Get testimonial data for display
     */
    public function getDisplayData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->testimonial_name,
            'position' => $this->testimonial_position,
            'content' => $this->testimonial_content,
            'short_content' => $this->short_content,
            'display_name' => $this->display_name,
            'formatted_date' => $this->formatted_date
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID when creating
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });

        // Log activity when testimonial is created
        static::created(function ($testimonial) {
            log_activity('Testimoni baru ditambahkan: ' . $testimonial->testimonial_name, $testimonial);
        });

        // Log activity when testimonial is updated
        static::updated(function ($testimonial) {
            log_activity('Testimoni diperbarui: ' . $testimonial->testimonial_name, $testimonial);
        });

        // Log activity when testimonial is deleted
        static::deleted(function ($testimonial) {
            log_activity('Testimoni dihapus: ' . $testimonial->testimonial_name);
        });
    }
}