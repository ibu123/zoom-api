<?php

namespace App\Observers;

use App\Service\Zoom;
use App\Jobs\ScheduleZoomMeetings;
use App\Models\ConversationMessage;
use App\Http\Requests\StoreMessageRequest;

class ConversationMessageObserver
{
    protected $request;

    public function __construct(StoreMessageRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the ConversationMessages "created" event.
     *
     * @param  \App\Models\ConversationMessages  $conversationMessages
     * @return void
     */
    public function created(ConversationMessage $conversationMessages)
    {

        ScheduleZoomMeetings::dispatch($conversationMessages, 'created', $this->request->all());
    }

    /**
     * Handle the ConversationMessages "updated" event.
     *
     * @param  \App\Models\ConversationMessages  $conversationMessages
     * @return void
     */
    public function updated(ConversationMessage $conversationMessages)
    {

    }

    /**
     * Handle the ConversationMessages "deleted" event.
     *
     * @param  \App\Models\ConversationMessages  $conversationMessages
     * @return void
     */
    public function deleted(ConversationMessage $conversationMessages)
    {

    }

    /**
     * Handle the ConversationMessages "restored" event.
     *
     * @param  \App\Models\ConversationMessages  $conversationMessages
     * @return void
     */
    public function restored(ConversationMessage $conversationMessages)
    {
        //
    }

    /**
     * Handle the ConversationMessages "force deleted" event.
     *
     * @param  \App\Models\ConversationMessages  $conversationMessages
     * @return void
     */
    public function forceDeleted(ConversationMessage $conversationMessages)
    {
        //
    }
}
