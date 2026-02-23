<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'income_category_id',
        'income_name',
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

    public function incomeCategoryData()
    {
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }

    //To get all the expense data with category...
    public static function getIncomeDataWithCategory($categoryId)
    {
        $data = Income::where('income_category_id', $categoryId)->get();
        return $data;
    }
}
