<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\Facades\Schema;

class CreateZoomMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_message_id')->constrained('conversation_messages');
            $table->timestamp('start_time');
            $table->text('meeting_id');
            $table->text('start_url');
            $table->text('join_url');
            $table->text('password');
            $table->text('timezone');
            $table->text('topic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_meetings');
    }
}
