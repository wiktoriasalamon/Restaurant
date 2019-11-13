<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class NewOrderFromWorkerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return[
            'table_id' => 'required',
            'items' => 'required|array'
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'table_id.required'=>'Id stolika jest wymagane',
            'items.required'=>'Produkty są wymagane',
            'items.array'=>'Zły format produktów',
        ];
    }
}
