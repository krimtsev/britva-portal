<?php

namespace App\Models\Statement;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Statement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'statements';

    protected $fillable = [
        'title',
        'category_id',
        'partner_id',
        'user_id',
        'state'
    ];

    public const stateList = [
        1 => [
            "title" => "Новый",
            "key"   => "new",
        ],
        2 => [
            "title" => "В работе",
            "key"   => "work",
        ],
        3 => [
            "title" => "Ожидание",
            "key"   => "wait",
        ],
        4 => [
            "title" => "Решено",
            "key"   => "success",
        ],
        5 => [
            "title" => "Закрыто",
            "key"   => "close",
        ],
        6 => [
            "title" => "Отклонено",
            "key"   => "cancel",
        ],
    ];

    public const stateIdsClosed = [4, 5, 6];

    public function category() {
        return $this->belongsTo(StatementCategory::class, 'category_id');
    }

    public function partner() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function stateName() {
        return self::stateList[$this->state]["title"];
    }
}
