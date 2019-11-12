<?php

namespace App\Http\Requests\User;



class ResetPasswordRequest extends UserChangePasswordRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        $rules = parent::rules();
        unset($rules['oldPassword']);
        return $rules;
    }

    /**
     * @return array
     */
    public function messages():array
    {
        return parent::messages();
    }


}
