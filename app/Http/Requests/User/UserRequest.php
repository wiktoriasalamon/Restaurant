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
            'email' => 'required',
            'address' => 'required',
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
            'address.required' => 'To pole jest wymagane',
        ];
    }
}