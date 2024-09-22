<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadCategories extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'upload_categories';

    protected $fillable = [
        'name',
        'category_id',
        'slug',
    ];

    public function categories()
    {
        return $this->hasMany(UploadCategories::class);
    }

    public function childrenCategories()
    {
        return $this->hasMany(UploadCategories::class)->with('upload_categories');
    }
}
