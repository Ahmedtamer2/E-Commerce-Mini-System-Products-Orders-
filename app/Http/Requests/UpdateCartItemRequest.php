<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'يجب أن تكون الكمية رقماً صحيحاً',
            'quantity.min' => 'يجب أن تكون الكمية أكبر من الصفر'
        ];
    }
}