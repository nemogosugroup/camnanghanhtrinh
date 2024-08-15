<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VuLanHistory extends Model
{
    use HasFactory;

    protected $table = 'vulan_histories';
    protected $fillable = [
        'name',
        'user_id',
        'template_id',
        'content'
    ];

    public function template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\VuLanTemplate', 'template_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getContentAttribute(): array
    {
        return json_decode($this->attributes['content'], true) ?? [];
    }
}
