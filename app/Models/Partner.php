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
        'name',
        'contract_number',
        'email',
        'telnum_1',
        'telnum_2',
        'telnum_3',
        'yclients_id',
        'address',
        'start_at'
    ];

    public function user()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

}
