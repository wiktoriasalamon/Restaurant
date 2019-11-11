<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DishCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return[
            'name' => 'required'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required'=>'Nazwa kategorii jest wymagana',
        ];
    }
}
