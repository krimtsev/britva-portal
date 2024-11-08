<?php

namespace App\Models\Statement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatementCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'statements_categories';

    protected $fillable = [
        'title',
    ];
}
