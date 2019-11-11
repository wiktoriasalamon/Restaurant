<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class WorkerReservationRequest extends ReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['email'] = 'required|e-mail|max:50';
        $rules['tables']='tables_available:'. $this->request->get('date').','.$this->request->get('startTime');
        return $rules;


    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages = parent::messages();
        $messages['email.email'] = 'Niepoprawny adres e-mail.';
        $messages['email.max'] = 'Niepoprawny adres e-mail.';
        $messages['email.required'] =  'Adres e-mail jest wymagany.';
        $messages['tables.tables_available'] =  'Stolik jest niedostępny.';
        return $messages;

    }
}
