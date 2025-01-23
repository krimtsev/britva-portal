<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'photo',
        'partner_id',
        'role_id',
    ];

    const FOLDER = 'teams';

    public static $rolesList = [
        1 => [
            "id"   => 1,
            "name" => "Барбер",
            "tag"  => "BARBER",
        ],
        2 => [
            "id"   => 2,
            "name" => "Top барбер",
            "tag"  => "TOP_BARBER",
        ],
        3 => [
            "id"   => 3,
            "name" => "Бренд барбер",
            "tag"  => "BRAND_BARBER",
        ],
    ];

    public function role()
    {
        return self::$rolesList[$this->role_id]["name"];
    }

    public function partner() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
