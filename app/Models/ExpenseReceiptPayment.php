<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class ExpenseReceiptPayment extends Model
{
    protected $fillable = [
        'user_id',
        'expense_receipt_id',
        'total_product',
    	'total_amount',
    	'special_discount',
    	'net_amount',
    	'paid_amount',
    	'due_amount',
    	'change_amount',
    	'payment_note',
        'billing_month',
        'billing_date',
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
}
