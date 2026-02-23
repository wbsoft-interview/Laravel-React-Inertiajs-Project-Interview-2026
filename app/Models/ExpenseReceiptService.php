<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class ExpenseReceiptService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expense_receipt_id',
        'expense_category_id',
        'expense_id',
        'payee_id',
        'expense_details',
        'expense_amount',
        'grand_total_paid',
        'grand_total_due',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
    public function expenseReceiptData()
    {
        return $this->belongsTo(ExpenseReceipt::class,'expense_receipt_id');
    }
    
    public function expenseCategoryData()
    {
        return $this->belongsTo(ExpenseCategory::class,'expense_category_id');
    }
    
    public function expenseData()
    {
        return $this->belongsTo(Expense::class,'expense_id');
    }
    
    public function payeeData()
    {
        return $this->belongsTo(Payee::class,'payee_id');
    }

    //To get all the expense reciect service qty...
    public static function getTotalExpenseReceiptServiceQty($expenseReceiptId)
    {
        $data = ExpenseReceiptService::whereIn('expense_receipt_id', [$expenseReceiptId])->count();
        return $data;
    }
    
    //To get all the expense reciect service amount...
    public static function getTotalExpenseReceiptServiceAmount($expenseReceiptId)
    {
        $data = ExpenseReceiptService::whereIn('expense_receipt_id', [$expenseReceiptId])->sum('expense_amount');
        return $data;
    }
}
