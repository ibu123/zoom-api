<?php

namespace App\Models;

use App\Models\ZoomMeeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversationMessage extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender_id', 'type', 'content', 'schedule_type'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(Contact::class);
    }

    public function zoom_actions()
    {
        return $this->hasOne(ZoomMeeting::class, 'conversation_message_id');

    }
}
