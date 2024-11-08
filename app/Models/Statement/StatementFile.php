<?php

namespace App\Models\Statement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementFile extends Model
{
    use HasFactory;

    protected $table = 'statements_files';

    const FOLDER = 'statements';

    protected $fillable = [
        'title',
        'name',
        'origin',
        'path',
        'type',
        'ext',
        'statement_message_id',
    ];
}
