<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Helpers\CurrentUser;

class ActivityLogger
{
    public static function action(
        string $action,
        string $description,
        $subject = null
    ): void {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id'    => CurrentUser::getOwnerId(),
            'access_by'  => CurrentUser::getUserIdFCU(),
            'ip_address' => request()->ip(),
            'action'     => $action,
            'module'     => $subject ? class_basename($subject) : 'System',
            'description'=> $description,
        ]);
    }
}
