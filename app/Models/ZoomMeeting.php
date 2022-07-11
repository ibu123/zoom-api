<?php

namespace App\Models;

use App\Models\ConversationMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_message_id', 'start_time', 'topic', 'timezone', 'start_url', 'join_url', 'password', 'meeting_id'];

    public function messages()
    {
        return $this->belongsTo(ConversationMessage::class, 'conversation_message_id', 'id');
    }
}
