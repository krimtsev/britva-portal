<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'upload';

    protected $fillable = [
        'name',
        'slug',
        'folder',
        'category_id',
    ];

    protected $casts = [
        'created_at'  => 'date:Y-m-d',
    ];

    public function category()
    {
        return $this->belongsTo(Upload::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Upload::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Upload::class, 'category_id');
    }

    public function files()
    {
        return $this->hasMany(UploadFile::class, 'upload_id', 'id');
    }
}
