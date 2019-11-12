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
        $rules['password'] = 'required|min:6';
        $rules['email']=$rules['email'].'|unique:users';

        return $rules;

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages = parent::messages();
        $messages['password.min'] = 'Hasło musi mieć przynajmniej 6 znaków';
        return $messages;
    }
}