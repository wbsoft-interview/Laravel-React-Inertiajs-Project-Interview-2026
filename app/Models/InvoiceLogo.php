<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\CurrentUser;
use \DateTimeInterface;

class InvoiceLogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_back_color',
        'invoice_terms',
        'logo_image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    //To get single logo...
    public static function getSoftwareInvoiceLogo()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = InvoiceLogo::where('user_id', $userId)->first();
        return $data;
    }
}
