<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipment extends Model
{
    use HasFactory;
    protected $table = 'type_equipment';
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'position'
    ];

    protected $appends = [
        'position_value'
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Equipment');
    }

    // get category
    public function category()
    {
        return $this->belongsTo('App\Models\CategoryEquipment');
    }

    public function getPositionValueAttribute()
    {
        return EQUIPMENT_POSITION[$this->position]['name'] ?? $this->position;
    }
}
