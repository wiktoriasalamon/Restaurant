<?php


namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class UserChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6',
            'newPasswordRepeated' => 'required|same:newPassword'
        ];
        return $rules;

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'oldPassword.required' => 'To pole jest wymagane',
            'newPassword.required' => 'To pole jest wymagane',
            'newPassword.min' => 'Hasło musi mieć przynajmniej 6 znaków',
            'newPasswordRepeated.required' => 'To pole jest wymagane',
            'newPasswordRepeated.same' => 'Hasła muszą być takie same',
        ];
    }
}