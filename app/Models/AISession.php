<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AISession extends Model
{
    protected $table = 'a_i_sessions';
    protected $fillable = ['client_ip', 'is_unresolved'];

    public function messages()
    {
        return $this->hasMany(AIMessage::class, 'a_i_session_id');
    }
}
