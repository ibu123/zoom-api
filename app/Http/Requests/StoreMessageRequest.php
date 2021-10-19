<?php

namespace App\Http\Requests;

use App\Models\Conversation;
use Illuminate\Validation\Rule;
use App\Rules\CheckMessageContent;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function prepareForValidation()
    {
        $this->merge([
            'conversation_id' => $this->segment(3)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'conversation_id' => 'required|exists:conversations,id',
            'sender_id' => 'required|exists:contacts,id|numeric|'.$this->ConversationContacts(),
            'type' =>  Rule::in(['Text', 'Meeting']),
            'content' => $this->type== 'Meeting' ? new CheckMessageContent($this) : 'required'
        ];
    }

    public function ConversationContacts()
    {
        if($conversation = Conversation::find($this->segment(3)))
            return Rule::in($conversation->participants->pluck('id')->toArray());

        return true;

    }

    protected function passedValidation()
    {
        if($this->type == 'Text')
        {
            $this->merge([
                'schedule_type' => 0,
            ]);
        }
    }

    public function messages()
    {
        return [
            'sender_id.*' => 'Invalid sender_id, either sender is not attached to this conversation'
        ];
    }
}
