<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    use HasFactory;
    protected $table = 'medals';
    protected $fillable = [
        'title', 'slug', 'category_id'
    ];
    public function category()
    {
        return $this->belongsTo('App\Models\CategoryMedal');
    }
    public function equipments()
    {
        return $this->hasMany('App\Models\Equipment');
    }
}
