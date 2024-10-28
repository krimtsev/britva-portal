<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audit';

    protected $fillable = [
        'type',
        'new',
        'old',
    ];

    const STAFF_TYPE = 'staff';
}
