<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class IdCardSetting extends Model
{
    protected $fillable = [
        'user_id',
        'organization_name',
        'institute_contact_no',
        'institute_contact_no_2',
        'institute_contact_email',
        'institute_code',
        'emis_code',
        'institute_established',
        'institute_address',
        'image_opacity',
        'logo_image',
        'hologram_image',
        'background_image',
        'seal_image',
        'sign_image',
        'logo_image',
        'frontend_logo_image',
        'frontend_back_logo_image',
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

    //To get single getIdCardSettingData data...
    public static function getIdCardSettingData()
    {
        $data = IdCardSetting::first();
        return $data;
    }
}
