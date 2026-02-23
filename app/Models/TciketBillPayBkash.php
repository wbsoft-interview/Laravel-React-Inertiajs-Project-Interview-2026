<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class TciketBillPayBkash extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticketing_id',
        'payment_id',
        'total_amount',
        'merchant_invoice_number',
        'currency',
        'intent',
        'is_bkash_payment',
        'is_bkash_execute',
        'token_id',
        'trx_id',
        'status'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function ticketingData()
    {
        return $this->belongsTo(AdminPackageHistory::class,'ticketing_id');
    }
}
