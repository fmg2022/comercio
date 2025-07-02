<?php

namespace App\Observers;

use App\Models\OrderProduct;

class OrderProductObserver
{
    /**
     * Handle the OrderProduct "created" event.
     */
    public function created(OrderProduct $orderProduct): void
    {
        //
    }

    /**
     * Handle the OrderProduct "updated" event.
     */
    public function updated(OrderProduct $orderProduct): void
    {
        $this->updatedOrderTotal($orderProduct->order);
    }

    /**
     * Handle the OrderProduct "deleted" event.
     */
    public function deleted(OrderProduct $orderProduct): void
    {
        //
    }

    /**
     * Handle the OrderProduct "restored" event.
     */
    public function restored(OrderProduct $orderProduct): void
    {
        //
    }

    /**
     * Handle the OrderProduct "force deleted" event.
     */
    public function forceDeleted(OrderProduct $orderProduct): void
    {
        //
    }

    protected function updatedOrderTotal($order)
    {
        $order->load('products');
        $newTotal = $order->products->sum(function ($product) {
            return ($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount;
        });

        $order->update([
            'total' => $newTotal,
            'updated_at' => now()
        ]);
    }
}
