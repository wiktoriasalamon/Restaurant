<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class CustomerReservationRequest extends ReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        array_push($rules['date'], 'one_a_day_reservation:' . $this->request->get('email'));
        $rules['tableSize'] = 'required|integer|between:1,6';
        if (!$this->request->get('phone')) {
            unset($rules['phone']);
        }
        if ($this->request->get('email')) {
            $rules['email'] = 'e-mail|max:50';
        }
        return $rules;


    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages = parent::messages();
        $messages['date.one_a_day_reservation'] = 'Limit rezerwacji na dzień wynosi jeden. W celu zarezerwowania stolików dla większej grupy prosimy o kontakt telefoniczny.';
        $messages['tableSize.required'] = 'Rozmiar stolika jest wymagany';
        $messages['tableSize.integer'] = 'Niepoprawny rozmiar stolika.';
        $messages['tableSize.between'] = 'Dostępne rozmiary stolików to: 1-6.';
        $messages['phone.max'] = 'Niepoprawna długość numer telefonu.';
        $messages['phone.regex'] = 'Niepoprawny numer telefonu.';
        $messages['email.email'] = 'Niepoprawny adres e-mail.';
        $messages['email.max'] = 'Niepoprawny adres e-mail.';
        return $messages;

    }
}
