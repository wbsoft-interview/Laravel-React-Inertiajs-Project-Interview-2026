<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Package extends Model
{
    protected $fillable = [
        'user_id',
        'package_category_id',
        'package_name',
        'package_price',
        'package_validity',
        'sms_qty',
        'student_qty',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
    public function packageCategoryData()
    {
        return $this->belongsTo(PackageCategory::class,'package_category_id');
    }
}
