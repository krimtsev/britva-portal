<?php

namespace App\Models\Statement;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public $stateList = [
        1 => "Выполняется",
        2 => "Готово"
    ];

    public function category() {
        return $this->belongsTo(StatementCategory::class, 'category_id');
    }

    public function partner() {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function stateName() {
        return $this->stateList[$this->state];
    }
}
