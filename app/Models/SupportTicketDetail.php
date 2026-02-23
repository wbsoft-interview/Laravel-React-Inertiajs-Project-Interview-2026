<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketDetail extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_by_id',
        'ticket_reply_id',
        'support_ticket_id',
        'subject',
        'details',
        'image',
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
    
    public function ticketReplyData()
    {
        return $this->belongsTo(User::class, 'ticket_reply_id');
    }
    
    public function supportTicketData()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }
}
