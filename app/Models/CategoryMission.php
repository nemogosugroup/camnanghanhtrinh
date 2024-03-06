<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMission extends Model
{
    use HasFactory;
    protected $table = 'category_mission';
    protected $fillable = [
        'title', 'slug'
    ];

    // lấy tất cả hạng mục thuộc về danh mục
    public function sub_categories()
    {
        return $this->hasMany('App\Models\SubCategoryMission', 'category_id', 'id');
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($category) {
    //         $category->childCategory()->get()->each->delete();
    //     });
    // }
}
