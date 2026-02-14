<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIMessage extends Model
{
    protected $table = 'a_i_messages';
    protected $fillable = ['a_i_session_id', 'role', 'content'];

    public function session()
    {
        return $this->belongsTo(AISession::class, 'a_i_session_id');
    }
}
