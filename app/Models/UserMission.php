<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMission extends Model
{
    use HasFactory;
    protected $table = 'user_missions';
    protected $fillable = [
        'user_id',
        'mission_id',
        'status'
    ];
    protected $appends = [
        //'data_mission',
        'position_equipment'
    ];
    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }
    // public function getDataMissionAttribute()
    // {
    //     return $this->mission->only(['id', 'equipment_id', 'gold', 'experience']);
    // }
    public function getPositionEquipmentAttribute(){
        $mission = $this->mission;
        if ($mission) {
            $equipment = $mission->equipment;
            if ($equipment) {
                $type = $equipment->type;
                if ($type) {
                    return $type->position;
                }
            }
        }
        return null;
    }
    public static function createOrNot($attributes)
    {
        $existingRecord = static::where('user_id', $attributes['user_id'])
                                 ->where('mission_id', $attributes['mission_id'])
                                 ->first();
        if ($existingRecord) {
            return $existingRecord;
        }
        return static::create($attributes);
    }
}

