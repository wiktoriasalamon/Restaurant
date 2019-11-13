<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class NewOrderOnlineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'takeaway' => 'required',
            'items' => 'required|array'
        ];
        if (!$this->takeaway){
            $rules['address'] = 'required';
        }
        return $rules;
        //mail lub auth w kontrolerze
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'takeaway.required'=>'Pole z odbiorem jest wymagane',
            'address.required'=>'Adres jest wymagany',
            'items.required'=>'Produkty są wymagane',
            'items.array'=>'Zły format produktów',
        ];
    }
}
