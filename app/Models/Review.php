<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'role',
        'content',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
