<?php

namespace App\Models\Ticket;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tickets';

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
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function partner() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function stateName() {
        return self::stateList[$this->state]["title"];
    }

    static function getStateNameById($id) {
        if (array_key_exists($id, self::stateList)) {
            return self::stateList[$id]["title"];
        }
        return "";
    }
}
