<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'levels';
    protected $fillable = [
        'level', 'experience', 'gold', 'equipment_id'
    ];
    protected $appends = [
        'position_equipment'
    ];
    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }
    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment', 'equipment_id', 'id');
    }
    public function getPositionEquipmentAttribute(){
        $equipment = $this->equipment;
        if ($equipment) {
            $type = $equipment->type;
            if ($type) {
                return $type->position;
            }
        }
        return null;
    }
}
