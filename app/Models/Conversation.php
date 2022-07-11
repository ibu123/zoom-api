<?php

namespace App\Models;

use App\Models\ConversationMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function participants()
    {
        return $this->belongsToMany(Contact::class, 'conversation_participants', 'conversation_id', 'sender_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class, 'conversation_id' , 'id');
    }

    public function lastTenMessages()
    {

        return $this->messages()->orderBy('created_at', 'desc')->limit(10);
    }
}
