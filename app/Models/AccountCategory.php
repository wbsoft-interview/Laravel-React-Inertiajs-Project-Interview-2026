<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class AccountCategory extends Model
{
    protected $fillable = [
        'user_id',
        'category_name',
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



    function accountCategoryData(){
        return $this->BelongsTo(AcountCategory::class,'account_category_id');
    }
}
