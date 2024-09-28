<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangoBlacklist extends Model
{
    use HasFactory;

    protected $table = "mango_blacklist";

    protected $fillable = [
        "number_id",
        "number",
        "number_type",
        "comment",
        "is_disabled",
    ];

    public static function addRecord($table) {
        self::updateOrCreate(
            [
                "number_id" => $table["number_id"],
            ],
            $table
        );
    }
}
