<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_by_id',
        'support_type',
        'ticket_number',
        'subject',
        'status'
    ];
    
    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticketByData()
    {
        return $this->belongsTo(User::class, 'ticket_by_id');
    }

    public function supportTicketDetailData()
    {
        return $this->hasMany(SupportTicketDetail::class);
    }
}
