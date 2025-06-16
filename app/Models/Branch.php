<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    /**
     * The table associated with the model.
     */
    protected $table = 'branches';

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
        'branch_name',
        'branch_address',
        'branch_operational_hours',
        'branch_google_maps_link',
        'branch_phone',
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
        'formatted_phone',
        'whatsapp_link',
        'maps_coordinates',
        'qr_code_url'
    ];

    /**
     * Relationship: Branch belongs to Business
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Accessor: Get formatted phone number for WhatsApp
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        return $this->branch_phone ? format_phone_wa($this->branch_phone) : null;
    }

    /**
     * Accessor: Get WhatsApp chat link
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        return $this->branch_phone ? whatsapp_link($this->branch_phone) : null;
    }

    /**
     * Accessor: Get Google Maps coordinates
     */
    public function getMapsCoordinatesAttribute(): ?array
    {
        return $this->branch_google_maps_link 
            ? extract_coordinates_from_maps_url($this->branch_google_maps_link) 
            : null;
    }

    /**
     * Accessor: Get QR code URL for branch
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        return generate_branch_qr_code($this);
    }

    /**
     * Accessor: Get operational status
     */
    public function getOperationalStatusAttribute(): array
    {
        return get_branch_operational_status($this->branch_operational_hours);
    }

    /**
     * Accessor: Get short address for display
     */
    public function getShortAddressAttribute(): string
    {
        return format_branch_address($this->branch_address, 50);
    }

    /**
     * Scope: Filter branches by business
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    /**
     * Scope: Filter branches with phone numbers
     */
    public function scopeWithPhone($query)
    {
        return $query->whereNotNull('branch_phone');
    }

    /**
     * Scope: Filter branches with Google Maps links
     */
    public function scopeWithMapsLink($query)
    {
        return $query->whereNotNull('branch_google_maps_link');
    }

    /**
     * Scope: Search branches by name or address
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('branch_name', 'like', "%{$search}%")
              ->orWhere('branch_address', 'like', "%{$search}%");
        });
    }

    /**
     * Check if branch has complete information
     */
    public function isComplete(): bool
    {
        return !empty($this->branch_name) &&
               !empty($this->branch_address) &&
               !empty($this->branch_operational_hours) &&
               !empty($this->branch_phone) &&
               !empty($this->branch_google_maps_link);
    }

    /**
     * Get completion percentage for this branch
     */
    public function getCompletionPercentage(): int
    {
        $totalFields = 5; // name, address, hours, phone, maps
        $completedFields = 3; // name, address, hours are required

        if (!empty($this->branch_phone)) $completedFields++;
        if (!empty($this->branch_google_maps_link)) $completedFields++;

        return round(($completedFields / $totalFields) * 100);
    }

    /**
     * Generate a share URL for this branch
     */
    public function getShareUrl(): string
    {
        return url('/branch/' . $this->id);
    }

    /**
     * Get the distance from a given coordinate
     */
    public function getDistanceFrom(float $latitude, float $longitude): ?float
    {
        $coordinates = $this->maps_coordinates;
        
        if (!$coordinates) {
            return null;
        }

        return get_branch_distance(
            $latitude,
            $longitude,
            $coordinates['latitude'],
            $coordinates['longitude']
        );
    }

    /**
     * Check if branch is currently operational
     */
    public function isCurrentlyOpen(): ?bool
    {
        $status = $this->operational_status;
        
        if ($status['status'] === 'open') {
            return true;
        } elseif ($status['status'] === 'closed') {
            return false;
        }
        
        return null; // Unknown status
    }

    /**
     * Get branch contact card data
     */
    public function getContactCard(): array
    {
        return [
            'name' => $this->branch_name,
            'business' => $this->business->business_name ?? '',
            'address' => $this->branch_address,
            'phone' => $this->formatted_phone,
            'whatsapp' => $this->whatsapp_link,
            'maps' => $this->branch_google_maps_link,
            'hours' => $this->branch_operational_hours,
            'qr_code' => $this->qr_code_url
        ];
    }

    /**
     * Generate vCard data for contact sharing
     */
    public function generateVCard(): string
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "ORG:" . ($this->business->business_name ?? '') . "\n";
        $vcard .= "FN:" . $this->branch_name . "\n";
        $vcard .= "ADR:;;;" . $this->branch_address . ";;;;\n";
        
        if ($this->branch_phone) {
            $vcard .= "TEL:" . $this->formatted_phone . "\n";
        }
        
        if ($this->branch_google_maps_link) {
            $vcard .= "URL:" . $this->branch_google_maps_link . "\n";
        }
        
        $vcard .= "NOTE:Jam Operasional: " . $this->branch_operational_hours . "\n";
        $vcard .= "END:VCARD";

        return $vcard;
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

        // Log activity when branch is created
        static::created(function ($branch) {
            log_activity('Cabang baru dibuat: ' . $branch->branch_name, $branch);
        });

        // Log activity when branch is updated
        static::updated(function ($branch) {
            log_activity('Cabang diperbarui: ' . $branch->branch_name, $branch);
        });

        // Log activity when branch is deleted
        static::deleted(function ($branch) {
            log_activity('Cabang dihapus: ' . $branch->branch_name);
        });
    }
}