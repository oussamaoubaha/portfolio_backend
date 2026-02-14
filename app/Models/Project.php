<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'image_url', 'project_url', 'technologies', 'order'
    ];

    protected $casts = [
        'technologies' => 'array',
    ];
}
