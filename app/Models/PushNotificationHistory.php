<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class PushNotificationHistory extends Model
{
    protected $fillable = [
        'user_id',
        'sms_from_id',
        'sms_to_id',
        'sms_template_id',
        'push_notification_id',
        'title',
        'details',
        'status'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function smsFromData()
    {
        return $this->belongsTo(User::class, 'sms_from_id');
    }
    
    public function smsToData()
    {
        return $this->belongsTo(User::class, 'sms_to_id');
    }

    public function smsTemplateData()
    {
        return $this->belongsTo(SMSTemplate::class, 'sms_template_id');
    }
    
    public function pushNotificationData()
    {
        return $this->belongsTo(PushNotification::class, 'push_notification_id');
    }
}
