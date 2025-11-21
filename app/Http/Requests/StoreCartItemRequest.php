<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'معرف المنتج مطلوب',
            'product_id.exists' => 'المنتج المحدد غير موجود',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.integer' => 'يجب أن تكون الكمية رقماً صحيحاً',
            'quantity.min' => 'يجب أن تكون الكمية أكبر من الصفر'
        ];
    }
}