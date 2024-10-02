<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    use HasFactory;

    protected $table = 'upload_files';

    const FOLDER = 'cloud';

    protected $fillable = [
        'title',
        'name',
        'origin',
        'path',
        'type',
        'downloads',
        'ext',
        'upload_id',
    ];

    public function folders()
    {
        return $this->belongsTo(Upload::class, 'id', 'upload_id');
    }

    public function folder() {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }
}
