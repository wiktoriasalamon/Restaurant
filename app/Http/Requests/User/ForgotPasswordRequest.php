<?php


namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'To pole jest wymagane',
            'email.email' => 'Niepoprawny adres e-mail'
        ];
    }
}