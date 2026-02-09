<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'company',
        'location',
        'period',
        'type',
        'description',
        'missions',
    ];

    protected $casts = [
        'missions' => 'array',
    ];
}
