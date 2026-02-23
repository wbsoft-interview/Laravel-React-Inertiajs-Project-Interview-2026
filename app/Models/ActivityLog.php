<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'access_by',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   
    public function accessByData()
    {
        return $this->belongsTo(User::class, 'access_by');
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    protected static function booted()
    {
        static::creating(function ($log) {
            if (!isset($log->user_id) && auth()->check()) {
                $log->user_id = auth()->id();
            }

            if (!isset($log->access_by) && auth()->check()) {
                $log->access_by = auth()->id();
            }
        });
    }
}
