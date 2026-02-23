<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use DateTimeZone;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
    	'division_id',
    	'name_en',
        'name_bn',
        'latitude',
        'longitude',
    	'url',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->setTimezone(new DateTimeZone('Asia/Dhaka'))
                    ->format('Y-m-d H:i:s');
    }

    public function divisionData()
    {
        return $this->belongsTo(Division::class,'division_id');
    }
}
