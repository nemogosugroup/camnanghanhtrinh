<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory;
    protected $table = 'user_courses';
    protected $fillable = [
        'user_id',
        'course_id',
    ];
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
