<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\CurrentUser;
use \DateTimeInterface;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'logo_image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //To get single logo...
    public static function getSoftwareLogo()
    {
        //To get current user...
        $userId = CurrentUser::getOwnerId();
        $data = Logo::where('user_id', $userId)->first();
        return $data;
    }
}
