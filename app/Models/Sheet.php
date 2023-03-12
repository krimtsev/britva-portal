<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sheets';

    protected $fillable = [
        'title',
        'spreadsheet_id',
        'spreadsheet_name',
        'spreadsheet_range',
        'slug',
        'table_header',
        'is_published',
        'user_id'
    ];

    public $statusList = [
        0 => "Отключен",
        1 => "Опубликован",
    ];

    public function status()
    {

        if (array_key_exists($this->is_published, $this->statusList)) {
            return $this->statusList[$this->is_published];
        }

        return false;
    }

    public function user()
    {
        return $this->belongsTo(Sheet::class, 'sheet_id');
    }
}
