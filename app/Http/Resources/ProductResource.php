<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'status' => $this->status,
            'image' => $this->image,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => $this->resource->wasRecentlyCreated 
                ? 'تم إنشاء المنتج بنجاح' 
                : 'تمت العملية بنجاح',
        ];
    }
}