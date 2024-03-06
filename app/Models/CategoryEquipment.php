<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryEquipment extends Model
{
    use HasFactory;
    protected $table = 'category_equipment';
    protected $fillable = [
        'title', 'slug'
    ];

    public function type()
    {
        return $this->hasMany('App\Models\TypeEquipment');
    }
}
