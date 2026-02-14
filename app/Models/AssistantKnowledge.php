<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistantKnowledge extends Model
{
    use HasFactory;
    
    protected $table = 'assistant_knowledge';

    protected $fillable = ['question', 'keywords', 'answer'];
}
