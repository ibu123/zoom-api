<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->when(empty($request->segment(4)) ,$this->id),
            'createdAt' => $this->created_at,
            'content' => $this->content,
            'senderName' => $this->sender->name,
            'senderId' => $this->sender->id,
            'type' => $this->type
        ];

        return parent::toArray($request);
    }
}
