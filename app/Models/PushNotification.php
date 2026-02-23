<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sms_template_id',
        'role_id',
        'sending_date',
        'sending_time',
        'status',
        'is_scheduler',
        'is_sent',
        'sent_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function smsTemplateData()
    {
        return $this->belongsTo(SMSTemplate::class, 'sms_template_id');
    }
}
