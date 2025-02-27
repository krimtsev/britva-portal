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
        'staff_id',
        'company_id',
        'name',
        'specialization',
        'is_fired',
    ];

    public static function addRecord($table) {
        self::updateOrCreate(
            [
                "staff_id" => $table["staff_id"],
            ],
            $table
        );
    }
}
