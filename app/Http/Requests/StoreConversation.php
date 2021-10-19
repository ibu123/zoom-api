<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversation extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'participants' => 'required',
            'participants.*' => 'exists:contacts,id'
        ];
    }

    public function messages()
    {
        return [
            'participants.*.exists' => 'Participant does not exists',

        ];
    }
}
