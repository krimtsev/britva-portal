<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    use HasFactory;

    protected $table = 'tickets_files';

    const FOLDER = 'tickets';

    protected $fillable = [
        'title',
        'name',
        'origin',
        'path',
        'type',
        'ext',
        'ticket_message_id',
    ];
}
