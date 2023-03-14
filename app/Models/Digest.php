<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Digest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'digests';

    protected $fillable = [
        'title',
        'description',
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
        return $this->belongsTo(Digest::class, 'digest_id');
    }

}
