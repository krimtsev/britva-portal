<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'tg_chat_id',
        'yclients_id',
        'staff_id',
        'action'
    ];

    public static function add($table) {
        self::updateOrCreate(
            [
                "tg_chat_id"  => $table["tg_chat_id"],
            ],
            $table
        );
    }
}
