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
        'description'
    ];

    const FOLDER = 'teams';

    public static $rolesList = [
        1 => [
            "id"   => 1,
            "name" => "БАРБЕР",
            "tag"  => "BARBER",
        ],
        2 => [
            "id"   => 2,
            "name" => "TОП-БАРБЕР",
            "tag"  => "TOP_BARBER",
        ],
        3 => [
            "id"   => 3,
            "name" => "БРЕНД-БАРБЕР",
            "tag"  => "BRAND_BARBER",
        ],
        4 => [
            "id"   => 4,
            "name" => "БРЕНД-БАРБЕР+",
            "tag"  => "BRAND_BARBER_PLUS",
        ],
        5 => [
            "id"   => 5,
            "name" => "ЭКСПЕРТ",
            "tag"  => "EXPERT",
        ],
        6 => [
            "id"   => 6,
            "name" => "АДМИНИСТРАТОР",
            "tag"  => "ADMINISTRATOR",
        ],
        7 => [
            "id"   => 7,
            "name" => "РУКОВОДИТЕЛЬ",
            "tag"  => "MANAGER",
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
