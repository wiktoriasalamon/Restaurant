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
            'token' => 'required',
            'status' => 'required'
        ];

    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'token.required'=>'Token zamówienia jest wymagany',
            'status.required'=>'Status jest wymagany'
        ];
    }
}
