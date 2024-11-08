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
        1 => "Новый",
        2 => "В работе",
        3 => "Ожидание",
        4 => "Решено",
        5 => "Закрыт",
        6 => "Отклонено"
    ];

    public function category() {
        return $this->belongsTo(StatementCategory::class, 'category_id');
    }

    public function partner() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function stateName() {
        return self::stateList[$this->state];
    }

    public function daysInWork() {
        $created = new Carbon($this->created_at);

        if (in_array($this->state, [4,5,6])) {
            $updated = Carbon::now($this->updated_at);
            return $created->diff($updated)->days;
        }

        $now = Carbon::now();
        return $created->diff($now)->days;
    }


}
