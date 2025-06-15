<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'is_suspended',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_suspended' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Log when user is created
        static::created(function ($user) {
            log_activity_for($user, 'User account created', $user);
        });

        // Log when user is updated
        static::updated(function ($user) {
            if ($user->wasChanged(['is_active', 'is_suspended', 'role'])) {
                log_activity('User status updated', $user, [
                    'old' => $user->getOriginal(),
                    'new' => $user->getAttributes()
                ]);
            }
        });
    }

    /**
     * Get the business that belongs to the user.
     */
    public function business()
    {
        return $this->hasOne(Business::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the reports made by this user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the reports handled by this admin.
     */
    public function handledReports()
    {
        return $this->hasMany(Report::class, 'handled_by');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->is_suspended;
    }

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_suspended', false);
    }

    /**
     * Scope for admin users only
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for regular users only
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->email;
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        return user_initials($this->name);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): string
    {
        return format_phone_wa($this->phone);
    }

    /**
     * Get WhatsApp link
     */
    public function getWhatsappLinkAttribute(): string
    {
        return whatsapp_link($this->phone);
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        return avatar_url($this->email);
    }

    /**
     * Send email verification notification
     */
    public function sendEmailVerificationNotification()
    {
        // Log the verification email send using helper
        log_activity_for($this, 'Email verification sent', $this);

        // Send the notification
        parent::sendEmailVerificationNotification();
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        $result = parent::markEmailAsVerified();
        
        if ($result) {
            log_activity_for($this, 'Email verified', $this);
        }
        
        return $result;
    }

    /**
     * Suspend user account
     */
    public function suspend($reason = null)
    {
        $this->update([
            'is_suspended' => true,
        ]);

        log_activity('User account suspended', $this, ['reason' => $reason]);

        return $this;
    }

    /**
     * Activate user account
     */
    public function activate()
    {
        $this->update([
            'is_active' => true,
            'is_suspended' => false,
        ]);

        log_activity('User account activated', $this);

        return $this;
    }

    /**
     * Get user's business completion percentage
     */
    public function getBusinessCompletionAttribute(): int
    {
        return business_completion($this->business);
    }

    /**
     * Get user's business URL
     */
    public function getBusinessUrlAttribute(): ?string
    {
        if (!$this->business || !$this->business->public_url) {
            return null;
        }

        return route('public.business.show', $this->business->public_url);
    }

    /**
     * Check if user has completed business setup
     */
    public function hasCompletedBusinessSetup(): bool
    {
        return $this->business && $this->business->progress_completion >= 80;
    }

    /**
     * Get recent activity logs
     */
    public function getRecentActivities($limit = 10)
    {
        return $this->activityLogs()
            ->with(['user'])
            ->limit($limit)
            ->get();
    }
}