<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tickets_categories';

    protected $fillable = [
        'title',
    ];
}
