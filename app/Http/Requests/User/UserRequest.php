<?php


namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'address' => 'required|json|string',
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
            'name.required' => 'To pole jest wymagane',
            'surname.required' => 'To pole jest wymagane',
            'email.required' => 'To pole jest wymagane',
            'email.email' => 'Niepoprawny adres e-mail',
            'address.required' => 'To pole jest wymagane',
            'address.json' => 'Nieprawidłowy format',
            'phone.max' => 'Niepoprawna długość numer telefonu',
            'phone.regex' => 'Niepoprawny numer telefonu',
        ];
    }
}