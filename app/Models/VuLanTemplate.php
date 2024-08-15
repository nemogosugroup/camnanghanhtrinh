<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VuLanTemplate extends Model
{
    use HasFactory;

    protected $table = 'vulan_templates';
    protected $fillable = [
        'title',
        'demo_img',
        'content'
    ];

    public function getContentAttribute(): array
    {
        return json_decode($this->attributes['content'], true) ?? [];
    }
}
