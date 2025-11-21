<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total price for this order item.
     */
    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($orderItem) {
            // Set the price from the product if not set
            if (empty($orderItem->price) && $orderItem->product) {
                $orderItem->price = $orderItem->product->price;
            }
        });

        static::created(function ($orderItem) {
            // Decrease product stock when order item is created
            if ($orderItem->product) {
                $orderItem->product->decreaseStock($orderItem->quantity);
            }
        });

        static::deleted(function ($orderItem) {
            // Increase product stock when order item is deleted
            if ($orderItem->product) {
                $orderItem->product->increaseStock($orderItem->quantity);
            }
        });
    }
}