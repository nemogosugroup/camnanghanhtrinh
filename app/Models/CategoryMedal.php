<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMedal extends Model
{
    use HasFactory;
    protected $table = 'category_medals';
    protected $fillable = [
        'title', 'slug'
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Medal');
    }
}
