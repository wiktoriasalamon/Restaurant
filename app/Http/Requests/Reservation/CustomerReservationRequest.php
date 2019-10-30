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
            'startTime'=>'required|enough_time_before:'.$this->request->get('date'),
            'date'=>'required|one_a_day:'.$this->request->get('email'), //customer can have one reservation per day
            'tableSize'=>'required'
        ];
        //phone is not obligatory
        if($this->request->get('phone')){
            $rules['phone'] = array('max:12','regex:/^[+]{1}(48)[0-9]{9}$|^[0-9]{9}$/');
        }
        //if worker adds reservation for a bigger group
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
