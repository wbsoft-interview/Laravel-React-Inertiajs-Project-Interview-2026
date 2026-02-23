<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use DateTimeZone;

class Division extends Model
{
    protected $fillable = [
    	'name_en',
        'name_bn',
    	'url',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new DateTimeZone('Asia/Dhaka'))
                    ->format('Y-m-d H:i:s');
    }
}
