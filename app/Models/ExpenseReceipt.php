<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class ExpenseReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by',
        'expense_receipt_id',
        'receipt_by',
        'receipt_notes',
        'status',
        'is_bill_pay',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function createdByData()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function expenseReceiptPaymentData()
    {
        return $this->hasOne(ExpenseReceiptPayment::class,'expense_receipt_id');
    }

    //To fetch pending ExpenseReceipt data...
    public static function getPendingExpenseReceiptData($userId, $userIdFCU)
    {
        $data = ExpenseReceipt::where('status', false)->where('user_id', $userId)->where('created_by', $userIdFCU)->pluck('id')->toArray();
        return $data;
    }
}
