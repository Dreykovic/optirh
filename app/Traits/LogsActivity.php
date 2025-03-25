<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        /*
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
        */
    }

    public function logActivity($action)
    {
        $description = $this->getActivityDescription($action);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'description' => $description,
            'old_values' => $action === 'updated' ? $this->getOriginal() : null,
            'new_values' => $action === 'updated' || $action === 'created' ? $this->getAttributes() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    protected function getActivityDescription($action)
    {
        $modelName = class_basename($this);
        return "{$modelName} a été {$action}";
    }
}
