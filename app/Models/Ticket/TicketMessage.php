<?php

namespace App\Models\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tickets_messages';

    protected $fillable = [
        'text',
        'ticket_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files() {
        return $this->hasMany(TicketFile::class, 'ticket_message_id', 'id');
    }
}
