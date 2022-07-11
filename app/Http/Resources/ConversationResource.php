<?php

namespace App\Http\Resources;

use App\Models\ConversationMessage;
use App\Http\Resources\ParticipantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->messages);
        return [

            'title' => $this->title,
            'senderName' => $this->lastTenMessages->isNotEmpty() ? $this->lastTenMessages[0]->sender->name : '',
            'lastMessage' => $this->when(empty($request->segment(3)), $this->lastTenMessages->isNotEmpty() ? $this->lastTenMessages[0]->content : ''),
                $this->mergeWhen(!empty($request->segment(3)), [
                    'participants' => ParticipantResource::collection($this->participants),
                    'messages' => ConversationMessageResource::collection($this->lastTenMessages)
                ])

          ];
    }
}
