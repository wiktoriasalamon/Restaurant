<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
                'date_format:H:i',
                'open_hours',
                'time_for_same_day_reservation:' . $this->request->get('date')
            ],
            'date' => [
                'required',
                'date',
                'after:yesterday'
            ],
            'phone' =>[
                'max:12',
                'regex:/^[+]{1}(48)[0-9]{9}$|^[0-9]{9}$/'
            ]
        ];
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
            'date.required'=>'Data rezerwacji jest wymagana',
            'date.date'=>'Format daty jest niepoprawny.',
            'date.after'=>'Data jest niepoprawna.',
            'phone.max' => 'Niepoprawna długość numer telefonu.',
            'phone.regex' => 'Niepoprawny numer telefonu.',
            'startTime.time_for_same_day_reservation' => 'Błędny czas.'
        ];
    }
}
