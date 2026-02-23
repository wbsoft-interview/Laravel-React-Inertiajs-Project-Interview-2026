<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class AdminPackageHistory extends Model
{
    protected $fillable = [
        'user_id',
        'package_by',
        'assigned_by',
        'package_id',
        'start_date',
        'end_date',
        'sms_qty',
        'student_qty',
        'status',
        'ticketing_status',
        'is_ticketing_pay',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function packageData()
    {
        return $this->belongsTo(Package::class,'package_id');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function assignedData()
    {
        return $this->belongsTo(User::class,'assigned_by');
    }
    
    public function packageByData()
    {
        return $this->belongsTo(User::class,'package_by');
    }
}
