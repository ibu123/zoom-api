<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Contact;
use App\Models\Conversation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_required_field_for_conversation()
    {
        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversations.store') , []);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "title" => ["The title field is required."],
                "participants" => ["The participants field is required."]
            ]
        ]);
    }

    public function test_paritipants_must_exist_for_conversation()
    {
        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversations.store') , [
            "title" => "hiii",
            "participants" => [ 0 , 0]
        ]);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "participants.0" => ["Participant does not exists"],
                "participants.1" => ["Participant does not exists"]
            ]
        ]);
    }

    public function test_can_create_conversation()
    {

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('conversations.store') , [
            "title" => "hiii",
            "participants" => [ 1 , 2 ]
        ]);

        $response->dump();
        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                "id"
            ]
        ]);
    }

    public function test_can_lists_conversation()
    {

        Conversation::factory()->count(2)->create();

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('conversations.index'));


        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                '*' => [
                    "title",
                    "senderName",
                    "lastMessage"
                ]
            ]
        ]);
    }

    public function test_not_found_conversation()
    {

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('conversations.show', 0));

        $response->assertStatus(404)->assertJson([
            "error" => "Resource not found"
        ]);
    }

    public function test_can_show_conversation()
    {
        $conversation = Conversation::factory() ->create();

        $response = $this
        ->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('conversations.show',$conversation->id));

        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                "title",
                "senderName",
                "participants" => [],
                "messages" => []
            ]
        ]);
    }

}
