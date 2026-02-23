<?php

namespace App\Observers;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    public function created(Model $model)
    {
        ActivityLogger::action(
            'create',
            "Created new " . class_basename($model) . " named {$this->name($model)}.",
            $model
        );
    }

    public function updated(Model $model)
    {
        if ($model instanceof \App\Models\User) {
            $ignored = ['remember_token', 'last_login_at', 'updated_at'];

            if (!$model->isDirty(array_diff(array_keys($model->getChanges()), $ignored))) {
                return;
            }
        }
        
        ActivityLogger::action(
            'update',
            "Updated " . class_basename($model) . " named {$this->name($model)}.",
            $model
        );
    }

    public function deleted(Model $model)
    {
        ActivityLogger::action(
            'delete',
            "Deleted " . class_basename($model) . " named {$this->name($model)}.",
            $model
        );
    }

    protected function name(Model $model): string
    {
        return $model->name
            ?? $model->title
            ?? '#' . $model->id;
    }
}
