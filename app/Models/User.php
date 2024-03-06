<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    public $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'profile_id',
        'employee_id',
        'avatar',
        'first_name',
        'last_name',
        'dept',
        'area',
        'dept',
        'department',
        'job',
        'birthday',
        'phone',
        'gender',
        'achievements',
        'experience',
        'gold',
        'flag',
        'level'
    ];

    protected $appends = [
        'fullname',
        'achievement_user',
        'exp_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'achievements' => 'json',
    ];  
    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getGenderAttribute()
    {
        return $this->attributes['gender'] == 1 ? 'Nam' : 'Nữ';
    }
    public function getBirthdayAttribute()
    {       
        return date('d-m-Y', strtotime($this->attributes['birthday']));
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses', 'user_id', 'course_id');
    }
    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'user_equipments', 'user_id', 'equipment_id');
    }
    public function missions()
    {
        return $this->hasMany(UserMission::class, 'user_id', 'id');
    }
    public function levels()
    {
        return $this->hasOne(Level::class, 'id', 'level');
    }
    // public function levels()
    // {
    //     return $this->belongsTo(Level::class, 'level', 'id');
    // }

    // public function coursestest()
    // {
    //     return $this->belongsToMany(Course::class, 'user_courses', 'user_id', 'course_id');
    // }
    public function getAchievementUserAttribute()
    {
        $result = false;
        if ($this->attributes['achievements']) {
            $result = [];
            $achievements = json_decode($this->attributes['achievements'], true);
            $result['medal'] = Medal::find($achievements['medal']);
            $result['category'] = CategoryMedal::find($achievements['medal_category']);
        }
        return $result;
    }
    public function getExpLevelAttribute()
    {
        $result = 0;
        if ($this->attributes['level']) {
            $level = Level::find($this->attributes['level']);
            $result = $level['experience'];
        }
        return $result;
    }
}
