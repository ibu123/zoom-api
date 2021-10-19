<?php

namespace App\Rules;

use App\Http\Requests\StoreMessageRequest;
use Illuminate\Contracts\Validation\Rule;
use DateTime;

class CheckMessageContent implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $msg;
    protected $request;

    public function __construct(StoreMessageRequest $request)
    {
            $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {


        //RegExp For Schedule - ^Schedule\sfor\s[0-24]{2}\:[0-59]{2}\s\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])$
        //RegExp For Cancel - ^Cancel\s[0-24]{2}\:[0-59]{2}\s\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])$
        //RegExp For Rescheduling - ^Reschedule\s[0-24]{2}\:[0-59]{2}\s\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\sto\s[0-24]{2}\:[0-59]{2}\s\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])$

        $explodeContent = explode(" ", $value);
        if($this->validateRegExp($value, '/^Schedule\sfor\s([0-1][0-9]|2[0-4])\:[0-5][0-9]\s(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{2}$/'))
        {
            if($this->validateDateFormat($explodeContent[2]." ".$explodeContent[3]))
            {
                $type = 1;
                $date = [ $explodeContent[2]." ".$explodeContent[3]];
                $this->request->merge([
                    'schedule_type' => $type,
                    'date' => $date
                ]);
                return true;
            }

            $this->msg = "To Schedule Metting Content Must Be In 'Schedule for hh:mm dd/MM/YY' Format";
            return false;
        }
        elseif($this->validateRegExp($value, '/^Cancel\s([0-1][0-9]|2[0-4])\:[0-5][0-9]\s(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{2}$/'))
        {
            if($this->validateDateFormat($explodeContent[1]." ".$explodeContent[2]))
            {
                $type = 2;
                $date = [ $explodeContent[1]." ".$explodeContent[2]];
                $this->request->merge([
                    'schedule_type' => $type,
                    'date' => $date
                ]);
                // dd(request()->all());
                return true;

            }

            $this->msg = "To Cancel Metting Content Must Be In 'Cancel hh:mm dd/MM/YY";
            return false;
        }
        elseif($this->validateRegExp($value, ' /^Reschedule\s([0-1][0-9]|2[0-4])\:[0-5][0-9]\s(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{2}\sto\s([0-1][0-9]|2[0-4])\:[0-5][0-9]\s(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{2}$/'))
        {
            if($this->validateDateFormat($explodeContent[1]." ".$explodeContent[2]) && $this->validateDateFormat($explodeContent[4]." ".$explodeContent[5]))
            {
                $type = 3;
                $date = [ $explodeContent[1]." ".$explodeContent[2] , $explodeContent[4]." ".$explodeContent[5]];
                $this->request->merge([
                    'schedule_type' => $type,
                    'date' => $date
                ]);
                return true;
            }

            $this->msg = "To Reschedule Metting Content Must Be In 'Reschedule hh:mm dd/MM/YY to hh:mm dd/MM/YY'";
            return false;
        }


        $this->msg = [
            "To Schedule Metting Content Must Be In 'Schedule for hh:mm dd/MM/YY' Format",
            "To Cancel Metting Content Must Be In 'Cancel hh:mm dd/MM/YY' Format",
            "To Reschedule Metting Content Must Be In 'Reschedule hh:mm dd/MM/YY to hh:mm dd/MM/YY' Format"
        ];
        return false;
    }

    public function validateDateFormat($value, $format = 'H:i d/m/y')
    {

        $date = DateTime::createFromFormat('!'.$format, $value);

        return $date && $date->format($format) == $value;
    }

    public function validateRegExp($value, $pattern)
    {
        return preg_match($pattern, $value) > 0;
    }

    public function message()
    {
        return $this->msg;
    }
}
