<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return[
            'size' => 'required|numeric|min:1|max:12'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'size.required'=>'Wielkość jest wymagana',
            'size.numeric'=>'Błędna wielkość jest wymagana',
            'size.min'=>'Błędna wielkość jest wymagana',
            'size.max'=>'Błędna wielkość jest wymagana',
        ];
    }
}
