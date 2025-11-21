<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->get();
        return ApiResponse::sendResponse(200, 'تم جلب الطلبات بنجاح', $orders);
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->user_id !== auth()->id()) {
            return ApiResponse::sendResponse(403, 'غير مصرح', null, ['error' => 'غير مصرح لك بالوصول لهذا الطلب']);
        }
        return ApiResponse::sendResponse(200, 'تم جلب الطلب بنجاح', $order);
    }

    public function store(StoreOrderRequest $request)
    {
        $user = auth()->user();
        $cartItems = Carts::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return ApiResponse::sendResponse(400, 'سلة المشتريات فارغة', null, ['error' => 'سلة المشتريات فارغة']);
        }

        DB::beginTransaction();

        try {
            $orderItems = [];
            $totalAmount = 0;
            $itemsSummary = [];

            foreach ($cartItems as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);

                if (!$product) {
                    throw new Exception("المنتج غير موجود");
                }

                if ($product->stock < $cartItem->quantity) {
                    throw new Exception("الكمية غير متوفرة من المنتج: " . $product->name);
                }

                $itemTotal = bcmul((string)$product->price, (string)$cartItem->quantity, 2);
                $totalAmount = bcadd((string)$totalAmount, (string)$itemTotal, 2);

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'subtotal' => $itemTotal
                ];

                $itemsSummary[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => (float)$product->price,
                    'total' => (float)$itemTotal
                ];

                $product->decrement('stock', $cartItem->quantity);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address' => $request->address,
                'billing_address' => $request->address,
                'payment_status' => 'pending',
                'phone' => $request->phone,
            ]);

            $order->items()->createMany($orderItems);
            Carts::where('user_id', $user->id)->delete();

            DB::commit();

            $responseData = [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->id,
                    'total' => (float)$totalAmount,
                    'status' => 'pending',
                    'created_at' => $order->created_at->toDateTimeString(),
                    'shipping_address' => $request->address,
                    'phone' => $request->phone,
                ],
                'items' => $itemsSummary
            ];

            return ApiResponse::sendResponse(201, 'تم إنشاء الطلب بنجاح', $responseData);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::sendResponse(500, 'فشل في إنشاء الطلب', null, ['error' => $e->getMessage()]);
        }
    }

    protected function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}