<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use App\Models\User;
use App\Models\PackageCategory;
use App\Models\Package;
use App\Models\AdminPackage;
use App\Helpers\CurrentUser;

class AdminPackage extends Model
{
    protected $fillable = [
        'user_id',
        'package_by',
        'package_id',
        'start_date',
        'end_date',
        'sms_remaining',
        'student_remaining',
        'status',
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
    
    public function packageByData()
    {
        return $this->belongsTo(User::class,'package_by');
    }

    //To get single admin package data...
    public static function getSinglePackageData($adminId)
    {
        $data = AdminPackage::with('packageData')->where('package_by', $adminId)->first();
 
        return $data;
    }
    
    //To get single admin package data...
    public static function getPackageData($adminId)
    {
        $userOwnerId = CurrentUser::getOwnerId();

        $adminPackage = AdminPackage::with('packageData')
            ->where('package_by', $adminId)
            ->first();

        if (!$adminPackage || !$adminPackage->packageData) {
            return collect([]);
        }

        return Package::where('user_id', $userOwnerId)
            ->where('package_category_id', $adminPackage->packageData->package_category_id)
            ->where('status', true)
            ->orderBy('package_name', 'asc')
            ->get();
    }

}
