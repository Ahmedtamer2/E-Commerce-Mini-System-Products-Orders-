<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ApiResponse::sendResponse(
            200,
            'تم استرجاع المنتجات بنجاح',
            ProductResource::collection($products)
        );
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create($validated);
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::sendResponse(404, 'المنتج غير موجود');
        }

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::sendResponse(404, 'المنتج غير موجود');
        }

        $validated = $request->validated();
        $product->update($validated);

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::sendResponse(404, 'المنتج غير موجود');
        }

        $product->delete();
        return ApiResponse::sendResponse(200, 'تم حذف المنتج بنجاح');
    }
}
