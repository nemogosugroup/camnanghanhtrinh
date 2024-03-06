<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = [
        'title', 
        'slug', 
        'category_id',
        'description',
        'content',
        'url_image',
        'gold',
    ];
    public function category()
    {
        return $this->belongsTo('App\Models\CategoryCourse');
    }
}
