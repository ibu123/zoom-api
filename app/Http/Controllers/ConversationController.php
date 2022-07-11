<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Requests\StoreConversation;
use App\Http\Resources\ConversationResource;


class ConversationController extends Controller
{

    public function index()
    {
        return ConversationResource::collection(Conversation::with('lastTenMessages')->simplePaginate(15));
    }

    public function store(StoreConversation $request)
    {

        $conversation = Conversation::create([
            'title' => $request->title
        ]);

        $conversation->participants()->attach($request->participants);

        return response()->json(["data" => [
                "id" => $conversation->id
            ]
        ],200);
    }

    public function show($conversation)
    {
        return new ConversationResource(Conversation::with(['participants', 'lastTenMessages'])->findOrfail($conversation));
    }

}
