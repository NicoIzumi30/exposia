<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    /**
     * Log activity using builder pattern
     */
    public static function log($description = null)
    {
        return new ActivityBuilder($description);
    }

    /**
     * Simple activity logging
     */
    public static function create(string $action, $model = null, array $properties = [])
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->getKey() : null,
            'old_values' => $properties['old'] ?? null,
            'new_values' => $properties['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log activity for specific user
     */
    public static function logFor($user, string $action, $model = null, array $properties = [])
    {
        return ActivityLog::create([
            'user_id' => is_object($user) ? $user->id : $user,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->getKey() : null,
            'old_values' => $properties['old'] ?? null,
            'new_values' => $properties['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get recent activities for user
     */
    public static function getRecentActivities($userId = null, $limit = 10)
    {
        $userId = $userId ?? Auth::id();
        
        return ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities for specific model
     */
    public static function getModelActivities($model, $limit = 10)
    {
        return ActivityLog::where('model_type', get_class($model))
            ->where('model_id', $model->getKey())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

class ActivityBuilder
{
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
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}