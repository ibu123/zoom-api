<?php

namespace App\Jobs;

use DateTime;
use DateTimeZone;
use App\Service\Zoom;
use DateTimeInterface;
use App\Models\ZoomMeeting;
use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use App\Models\ConversationMessage;
use Illuminate\Support\Facades\Date;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ScheduleZoomMeetings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $conversationMessage;
    protected $type;
    protected $data;

    public function __construct(ConversationMessage $conversationMessage, $type, $data)
    {
        $this->conversationMessage = $conversationMessage;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if($this->data['schedule_type'] == 1)
        {
            $response = Zoom::getInstance()->post('/users/me/meetings',[
                'topic' => Conversation::find($this->conversationMessage->conversation_id)->title." - ".$this->data['date'][0],
                'type' => 2,
                'start_time' => DateTime::createFromFormat('!H:i d/m/y', $this->data['date'][0])->format(DateTimeInterface::ATOM)
            ]);

            $response['conversation_message_id'] = $this->conversationMessage->id;
            $response['meeting_id'] = $response['id'];
            $date  = new DateTime($response['start_time'], new DateTimeZone('GMT'));
            $response['start_time'] =  $date->setTimezone(new DateTimeZone($response['timezone']))->format('Y-m-d H:i:s');

            ZoomMeeting::create($response);

        }
        elseif($this->data['schedule_type'] == 2)
        {
            $date = DateTime::createFromFormat('H:i d/m/y', $this->data['date'][0])->format('Y-m-d H:i:s');
            $zoom = ZoomMeeting::whereHas('messages', function($q){
                $q->where(['conversation_id' => $this->data['conversation_id'], 'sender_id' => $this->data['sender_id']])
                ->whereIn('schedule_type',[1,3]);
            })->where('start_time',$date)->orderBy('created_at','desc')->first();

            if($zoom)
            {
                $response = Zoom::getInstance()->delete('/meetings/'.$zoom->meeting_id);
                $zoom->delete();
            }

        }
        elseif($this->data['schedule_type'] == 3)
        {
            $date = DateTime::createFromFormat('H:i d/m/y', $this->data['date'][0])->format('Y-m-d H:i:s');
            $zoom = ZoomMeeting::whereHas('messages', function($q){
                $q->where(['conversation_id' => $this->data['conversation_id'], 'sender_id' => $this->data['sender_id']])
                ->whereIn('schedule_type',[1,3]);
            })->where('start_time',$date)->orderBy('created_at','desc')->first();

            if($zoom)
            {
                $updatedTime = DateTime::createFromFormat('!H:i d/m/y', $this->data['date'][1]);
                $response = Zoom::getInstance()->patch('/meetings/'.$zoom->meeting_id, [
                    'topic' => Conversation::find($this->conversationMessage->conversation_id)->title." - ".$this->data['date'][1],
                    'start_time' => $updatedTime->format(DateTimeInterface::ATOM)
                ]);

                if($response['code'] == 204)
                {
                    ZoomMeeting::find($zoom->id)->update([
                        'start_time' => $updatedTime->format('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }
}
