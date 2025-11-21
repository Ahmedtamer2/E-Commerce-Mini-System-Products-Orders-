<?php

namespace App\Http\Controllers\Api;

use App\Models\Carts;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $items = Carts::with('product')->where('user_id', $user->id)->get();
        
        return ApiResponse::sendResponse(
            200,
            'تم استرجاع سلة التسوق بنجاح',
            CartItemResource::collection($items)
        );
    }

    public function add(StoreCartItemRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $cartItem = Carts::create([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        return ApiResponse::sendResponse(
            201,
            'تمت إضافة المنتج إلى السلة بنجاح',
            new CartItemResource($cartItem->load('product'))
        );
    }


    public function remove($id)
    {
        $item = Carts::find($id);
        if (!$item) {
            return ApiResponse::sendResponse(404, 'العنصر غير موجود في السلة');
        }
        
        $item->delete();
        return ApiResponse::sendResponse(200, 'تم حذف العنصر من السلة بنجاح');
    }

    public function clear()
    {
        $user = auth()->user();
        Carts::where('user_id', $user->id)->delete();
        return ApiResponse::sendResponse(200, 'تم تفريغ السلة بنجاح');
    }
}