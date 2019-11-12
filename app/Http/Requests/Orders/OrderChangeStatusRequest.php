<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderChangeStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return[
            'order_id' => 'required',
            'status' => 'required'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'order_id.required'=>'Id zamówienia jest wymagane',
            'status.required'=>'Status jest wymagany'
        ];
    }
}
