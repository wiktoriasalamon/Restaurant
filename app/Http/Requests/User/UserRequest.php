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
            'address' => 'required',
        ];
        $rules['address.street'] = ['required','regex:/^[^0-9]+$/','max:50'];
        $rules['address.houseNumber'] = 'required';
        $rules['address.city'] =  ['required','regex:/^[^0-9]+$/','max:50'];
        $rules['address.postCode'] = [
            'required',
            'regex:/^\d{2}-\d{3}$/'
        ];
        if ($this->request->get('phone') != "") {
            $rules['phone'] = array('max:12', 'regex:/^[+]{1}(48)[0-9]{9}$|^[0-9]{9}$/');
        }
        return $rules;

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages = [
            'name.required' => trans('app.field.required'),
            'surname.required' => trans('app.field.required'),
            'email.required' => trans('app.field.required'),
            'email.email' => 'Niepoprawny adres e-mail',
            'address.required' => 'To pole jest wymagane',
            'phone.max' => 'Niepoprawna długość numer telefonu',
            'phone.regex' => 'Niepoprawny numer telefonu',
            'address.street.required' => trans('app.field.required'),
            'address.street.regex' => trans('app.field.incorrect'),
            'address.street.max' => trans('app.field.max'),
            'address.houseNumber.required' => trans('app.field.required'),
            'address.city.regex' => trans('app.field.incorrect'),
            'address.city.required' => trans('app.field.required'),
            'address.city.max' => trans('app.field.max'),
            'address.postCode.regex' => trans('app.field.incorrect'),
            'address.postCode.required' => trans('app.field.required'),

        ];
        return $messages;
    }
}