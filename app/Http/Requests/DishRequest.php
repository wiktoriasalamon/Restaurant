<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return[
            'name' => 'required',
            'price' => 'required|numeric|min:0|max:1000',
            'category_id' => 'required'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required'=>'Nazwa dania jest wymagana',
            'price.required'=>'Cena dania jest wymagana',
            'price.numeric'=>'Błędna cena dania',
            'price.min'=>'Błędna cena dania',
            'price.max'=>'Błędna cena dania',
            'category_id.required'=>'Kategoria dania jest wymagana',
        ];
    }
}
