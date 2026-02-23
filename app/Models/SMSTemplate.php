<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class SMSTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'sms_title',
        'sms_details',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }
    
    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
