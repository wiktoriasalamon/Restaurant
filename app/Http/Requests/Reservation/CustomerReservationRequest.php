<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class CustomerReservationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules=[
            'startTime'=>'required|time_for_same_day_reservation',
            'date'=>'required|one_a_day_reservation|date|after:yesterday:'.$this->request->get('email'), //customer can have one reservation per day
            'tableSize'=>'required'
        ];
        if($this->request->get('phone')){
            $rules['phone'] = array('max:12','regex:/^[+]{1}(48)[0-9]{9}$|^[0-9]{9}$/');
        }
        if($this->request->get('email')){
            $rules['email'] = 'required|e-mail|max:50';
        }
        return $rules;


    }

    /**
     * @return array
     */
    public function messages(): array
    {

    }
}
