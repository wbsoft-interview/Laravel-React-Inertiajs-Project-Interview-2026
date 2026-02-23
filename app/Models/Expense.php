<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expense_category_id',
        'expense_name',
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

    public function expenseCategoryData()
    {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id');
    }

    //To get all the expense data with category...
    public static function getExpenseDataWithCategory($categoryId)
    {
        $data = Expense::where('expense_category_id', $categoryId)->get();
        return $data;
    }
}
