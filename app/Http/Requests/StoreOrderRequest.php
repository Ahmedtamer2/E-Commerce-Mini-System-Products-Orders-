<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'address.required' => 'حقل العنوان مطلوب',
            'phone.required' => 'حقل الهاتف مطلوب',
            'address.string' => 'يجب أن يكون العنوان نصًا',
            'phone.string' => 'يجب أن يكون الهاتف نصًا',
            'address.max' => 'يجب ألا يتجاوز العنوان 255 حرفًا',
            'phone.max' => 'يجب ألا يتجاوز الهاتف 20 حرفًا',
        ];
    }
}