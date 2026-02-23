<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class IncomeReceiptService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'income_receipt_id',
        'income_category_id',
        'income_id',
        'receiver_id',
        'income_details',
        'income_amount',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function incomeReceiptData()
    {
        return $this->belongsTo(IncomeReceipt::class,'income_receipt_id');
    }

    public function incomeCategoryData()
    {
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }

    public function incomeData()
    {
        return $this->belongsTo(Income::class,'income_id');
    }

    public function receiverData()
    {
        return $this->belongsTo(Receiver::class,'receiver_id');
    }

    //To get all the expense reciect service qty...
    public static function getTotalIncomeReceiptServiceQty($incomeReceiptId)
    {
        $data = IncomeReceiptService::whereIn('income_receipt_id', [$incomeReceiptId])->count();
        return $data;
    }

    //To get all the expense reciect service amount...
    public static function getTotalIncomeReceiptServiceAmount($incomeReceiptId)
    {
        $data = IncomeReceiptService::whereIn('income_receipt_id', [$incomeReceiptId])->sum('income_amount');
        return $data;
    }
}
