<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationMessageTest extends TestCase
{
    public function test_failed_if_conversation_id_invalid()
    {
        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversation.messages.store', 0) , []);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "conversation_id" => ["The selected conversation id is invalid."]

            ]
        ]);
    }

    public function test_failed_if_sender_id_invalid_or_not_related_to_conversation()
    {
        $conversation = Conversation::factory()->create();
        $contact = Contact::factory()->create();
        $conversation->participants()->attach([
            $contact->id
        ]);

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversation.messages.store', $conversation->id) , [
            "sender_id" => 35
        ]);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "sender_id" => ["Invalid sender_id, either sender is not attached to this conversation"]

            ]
        ]);
    }

    public function test_can_create_conversation_message(){
        $conversation = Conversation::factory()->create();
        $contact = Contact::factory()->create();
        $conversation->participants()->attach([
            $contact->id
        ]);

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversation.messages.store', $conversation->id) , [
            "sender_id" =>  $contact->id,
            "type" => "Text",
            "content" => "Hello"
        ]);

        $response->assertStatus(200)->assertJsonStructure([
           "data" => [
               "message_id"
           ]
        ]);
    }

    public function test_can_list_conversation_message(){
        $conversation = Conversation::factory()->create();
        $contact = Contact::factory()->create();
        $conversation->participants()->attach([
            $contact->id
        ]);

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('conversation.messages.index', $conversation->id));

        $response->dump();
        $response->assertStatus(200)->assertJsonStructure([
           "data" => [
               "*" => [
                   "id",
                    "createdAt",
                    "content",
                    "senderName",
                    "senderId",
                    "type"
               ],

           ]
        ]);
    }

}
