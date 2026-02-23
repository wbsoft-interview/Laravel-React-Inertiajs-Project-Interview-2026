<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use DateTimeZone;

class Zone extends Model
{
    protected $fillable = [
    	'district_id',
    	'upozila_id',
    	'name_en',
        'name_bn',
    	'url',
    	'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new DateTimeZone('Asia/Dhaka'))
                    ->format('Y-m-d H:i:s');
    }

    public function districtData()
    {
        return $this->belongsTo(District::class,'district_id');
    }
    
    public function upozilaData()
    {
        return $this->belongsTo(Upozila::class,'upozila_id');
    }
}
