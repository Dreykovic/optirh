<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log($action, $description = null, $model = null)
    {
        $log = [
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        if ($model) {
            $log['model_type'] = get_class($model);
            $log['model_id'] = $model->id;
        }

        return ActivityLog::create($log);
    }
}
