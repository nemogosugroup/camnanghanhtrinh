<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryMission extends Model
{
    use HasFactory;
    protected $table = 'sub_category_mission';
    protected $fillable = [
        'category_id', 'title', 'slug'
    ];
    // get all post
    public function posts()
    {
        return $this->hasMany('App\Models\Mission');
    }

     // get category
    public function category()
    {
        return $this->belongsTo('App\Models\CategoryMission');
    }
}
