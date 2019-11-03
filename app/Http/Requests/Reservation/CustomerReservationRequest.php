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
        $rules = [
            'startTime' => [
                'required',
                'time_for_same_day_reservation:' . $this->request->get('date'),
                'date_format:H:i',
                'open_hours'
            ],
            'date' => [
                'required',
                'date',
                'after:yesterday',
                'one_a_day_reservation:' . $this->request->get('email'), //customer can have one reservation per day
            ],
            'tableSize' => 'required|integer|between:1,6'
        ];
        if ($this->request->get('phone')) {
            $rules['phone'] = array('max:12', 'regex:/^[+]{1}(48)[0-9]{9}$|^[0-9]{9}$/');
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
        return [
            'startTime.required'=>'Czas rozpoczęcia rezerwacji jest wymagany',
            'startTime.date_format'=>'Format czasu jest niepoprawny.',
            'startTime.open_hours'=>'Restauracja jest czynna w godzinach '.config('reservation.openTime').' - '.config('reservation.closeTime').'.',
            'startTime.time_for_same_day_reservation'=>'Brak dostępnego stolika w podanym czasie.',
            'date.required'=>'Data rezerwacji jest wymagana',
            'date.date'=>'Format daty jest niepoprawny.',
            'date.after'=>'Data jest niepoprawna.',
            'date.one_a_day_reservation'=>'Limit rezerwacji na dzień wynosi jeden. W celu zarezerwowania stolików dla większej grupy prosimy o kontakt telefoniczny.',
            'tableSize.required'=>'Rozmiar stolika jest wymagany',
            'tableSize.integer'=>'Niepoprawny rozmiar stolika.',
            'tableSize.between'=>'Dostępne rozmiary stolików to: 1-6.',
            'phone.max'=>'Niepoprawna długość numer telefonu.',
            'phone.regex'=>'Niepoprawny numer telefonu.',
            'email.email'=>'Niepoprawny adres e-mail.',
            'email.max'=>'Niepoprawny adres e-mail.',
        ];
    }
}
