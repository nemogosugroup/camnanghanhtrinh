<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEquipment extends Model
{
    use HasFactory;
    protected $table = 'user_equipments';
    protected $fillable = [
        'user_id',
        'equipment_id',
        'position',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment', 'equipment_id');
    }
    public static function createOrNot($attributes)
    {
        $existingRecord = static::where('user_id', $attributes['user_id'])
                                 ->where('equipment_id', $attributes['equipment_id'])
                                 ->first();
        if ($existingRecord) {
            return $existingRecord;
        }
        return static::create($attributes);
    }  
}
