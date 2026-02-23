<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class IncomeReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_receipt_id',
        'user_id',
        'receipt_by',
        'receipt_notes',
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

    //To fetch pending ExpenseReceipt data...
    public static function getPendingIncomeReceiptData($userId)
    {
        $data = IncomeReceipt::where('status', false)->where('user_id', $userId)->pluck('id')->toArray();
        return $data;
    }
}
