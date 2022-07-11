<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Service\Zoom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ConversationMessage;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ConversationMessageResource;

class ConversationMessageController extends Controller
{

    public function index($conversation)
    {

        return ConversationMessageResource::collection(ConversationMessage::whereHas('conversation', function($query) use ($conversation){
            $query->where('id', $conversation);
        })->simplePaginate(15));
    }

    public function store($conversation, StoreMessageRequest $request)
    {

        $ConversationMessageID = ConversationMessage::create([
            'conversation_id' => $conversation,
            'sender_id' => $request->sender_id,
            'content' => $request->content,
            'type' => $request->type,
            'schedule_type' => $request->schedule_type
        ])->id;


        return response()->json(["data" => [
                "message_id" => $ConversationMessageID
            ]
        ],200);
    }

    public function show($conversation, $message)
    {
        return new ConversationMessageResource(ConversationMessage::whereHas('conversation', function($query) use ($conversation){
            $query->where('id', $conversation);
        })->with('sender')->findOrfail($message));
    }

}
