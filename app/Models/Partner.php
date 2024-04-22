<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'partners';

    protected $fillable = [
        'organization',
        'inn',
        'name',
        'contract_number',
        'email',
        'telnums',
        'yclients_id',
        'mango_telnum',
        'address',
        'start_at',
        'disabled',

        'tg_active',
        'tg_chat_id',
        'tg_pay_end'
    ];

    public function user()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

}
