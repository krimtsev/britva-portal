<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'upload';

    protected $fillable = [
        'title',
        'folder',
        'category_id',
    ];

    protected $casts = [
        'created_at'  => 'date:Y-m-d',
    ];

    public function category()
    {
        return $this->belongsTo(UploadCategories::class, 'category_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(UploadFile::class, 'upload_id', 'id');
    }

}
