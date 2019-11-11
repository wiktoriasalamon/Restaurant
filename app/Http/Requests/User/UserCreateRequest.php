<?php


namespace App\Http\Requests\User;


class UserCreateRequest extends UserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['password'] = 'required, min:8';
        return $rules;

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages = parent::messages();
        $messages['password.min'] = 'Hasło musi mieć przynajmniej 8 znaków';
        return $messages;
    }
}