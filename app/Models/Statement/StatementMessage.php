<?php

namespace App\Models\Statement;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatementMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'statements_messages';

    protected $fillable = [
        'text',
        'statement_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files() {
        return $this->hasMany(StatementFile::class, 'statement_message_id', 'id');
    }
}
