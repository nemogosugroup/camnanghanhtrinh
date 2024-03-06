<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMedal extends Model
{
    use HasFactory;
    protected $table = 'user_medals';
    protected $fillable = [
        'user_id',
        'medal_id',
        'status'
    ];
    public static function createOrNot($attributes)
    {
        $existingRecord = static::where('user_id', $attributes['user_id'])
                                 ->where('medal_id', $attributes['medal_id'])
                                 ->first();
        if ($existingRecord) {
            return $existingRecord;
        }
        return static::create($attributes);
    }

    public function medal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Medal::class, 'medal_id');
    }
}
