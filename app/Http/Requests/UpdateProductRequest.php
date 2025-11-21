<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل الاسم مطلوب',
            'name.string' => 'يجب أن يكون الاسم نصيًا',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا',
            'description.string' => 'يجب أن يكون الوصف نصيًا',
            'price.required' => 'حقل السعر مطلوب',
            'price.numeric' => 'يجب أن يكون السعر رقمًا',
            'price.min' => 'يجب أن يكون السعر أكبر من أو يساوي صفر',
            'stock.required' => 'حقل الكمية مطلوب',
            'stock.integer' => 'يجب أن تكون الكمية رقماً صحيحاً',
            'stock.min' => 'يجب أن تكون الكمية أكبر من أو تساوي صفر',
            'image.string' => 'يجب أن يكون رابط الصورة نصيًا',
            'image.max' => 'يجب ألا يتجاوز رابط الصورة 255 حرفًا',
        ];
    }
}