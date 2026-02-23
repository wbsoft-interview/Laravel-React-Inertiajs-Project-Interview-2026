<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class AccountTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_category_id',
        'account_id',
        'transfer_by',
        'transfer_type',
        'transfer_amount',
        'current_amount',
        'transfer_date',
        'transfer_purpuse',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function accountCategoryData()
    {
        return $this->belongsTo(AccountCategory::class,'account_category_id');
    }
    
    public function accountData()
    {
        return $this->belongsTo(Account::class,'account_id');
    }
    
    public function transferByData()
    {
        return $this->belongsTo(User::class,'transfer_by');
    }
}
