<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $table = 'equipment';
    protected $fillable = [
        'type_equipment_id',
        'medal_id',
        'title',
        'slug',
        'description_designer',
        'description_in_game',
        'url_image',
        'method',
        'gold'
    ];

    public function type()
    {
        return $this->belongsTo('App\Models\TypeEquipment', 'type_equipment_id');
    }

    public function medal()
    {
        return $this->belongsTo('App\Models\Medal', 'medal_id');
    }
    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }
}
