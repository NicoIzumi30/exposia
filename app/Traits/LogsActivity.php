<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an activity
     */
    public function logActivity(string $action, string $description = null, array $properties = [])
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'old_values' => $properties['old'] ?? null,
            'new_values' => $properties['new'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log activity for a specific user
     */
    public static function logActivityFor($user, string $action, string $description = null, array $properties = [])
    {
        return ActivityLog::create([
            'user_id' => is_object($user) ? $user->id : $user,
            'action' => $action,
            'model_type' => static::class,
            'model_id' => null,
            'old_values' => $properties['old'] ?? null,
            'new_values' => $properties['new'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get activities for this model
     */
    public function activities()
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where('model_type', get_class($this))
            ->orderBy('created_at', 'desc');
    }
}

// Helper function untuk mudah log activity
if (!function_exists('activity')) {
    function activity($description = null)
    {
        return new class($description) {
            private $description;
            private $causedBy;
            private $performedOn;
            private $withProperties = [];

            public function __construct($description = null)
            {
                $this->description = $description;
            }

            public function causedBy($causer)
            {
                $this->causedBy = $causer;
                return $this;
            }

            public function performedOn($subject)
            {
                $this->performedOn = $subject;
                return $this;
            }

            public function withProperties(array $properties)
            {
                $this->withProperties = $properties;
                return $this;
            }

            public function log(string $action)
            {
                return ActivityLog::create([
                    'user_id' => $this->causedBy ? $this->causedBy->id : Auth::id(),
                    'action' => $action,
                    'model_type' => $this->performedOn ? get_class($this->performedOn) : null,
                    'model_id' => $this->performedOn ? $this->performedOn->getKey() : null,
                    'old_values' => $this->withProperties['old'] ?? null,
                    'new_values' => $this->withProperties['new'] ?? null,
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                ]);
            }
        };
    }
}