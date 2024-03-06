<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mission extends Model
{
    use HasFactory;
    protected $table = 'missions';
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'sub_category_id',
        'description',
        'link',
        'target',
        'method',
        'equipment_id',
        'gold',
        'experience',
        'level_id'
    ];
    protected $appends = [
        //'childrent',
        //'gender'
    ];
    public function getTargetAttribute()
    {
        return $this->attributes['target'] ? true : false;
    }
    public function getParentIdAttribute()
    {
        return $this->attributes['parent_id'] ? $this->attributes['parent_id'] : null;
    }
    public function getEquipmentIdAttribute()
    {
        return $this->attributes['equipment_id'] ? $this->attributes['equipment_id'] : null;
    }
    public function category()
    {
        return $this->belongsTo('App\Models\SubCategoryMission', 'sub_category_id', 'id');
    }
    public function equipment()
    {
        return $this->hasOne('App\Models\Equipment', 'id', 'equipment_id');
    }
    public function level()
    {
        return $this->hasOne('App\Models\Level', 'id', 'level_id');
    }
    public function childrents()
    {
        return $this->hasMany(Mission::class, 'parent_id', 'id')->with(['userMissions']);
    }
    public function userMissions()
    {
        $userId = Auth::id();
        return $this->hasOne(UserMission::class, 'mission_id', 'id')->where('user_id', $userId);
    }
}
