<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderProduct;

class OrderProductObserver
{
    /**
     * Handle the OrderProduct "updating" event.
     */
    public function updating(OrderProduct $orderProduct): void
    {
        if ($orderProduct->quantity < 1) {
            throw new \Exception("La cantidad no debe ser menor a 1");
        }
    }

    /**
     * Handle the OrderProduct "created" event.
     */
    public function created(OrderProduct $orderProduct): void
    {
        $this->updatedOrderTotal($orderProduct->order);

        $this->decreaseProductStock($orderProduct);
    }

    /**
     * Handle the OrderProduct "updated" event.
     */
    public function updated(OrderProduct $orderProduct): void
    {
        $this->updatedOrderTotal($orderProduct->order);

        $this->updatedProductStock($orderProduct);
    }

    protected function updatedOrderTotal(Order $order): void
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

    protected function updatedProductStock(OrderProduct $orderProduct): void
    {
        $product = $orderProduct->product;
        $newStock = $product->getOriginal('quantity') - $orderProduct->quantity;

        if ($newStock < 0) {
            throw new \Exception("El stock del producto no puede ser negativo");
        }

        $product->update(['quantity' => $newStock]);
    }

    protected function decreaseProductStock(OrderProduct $orderProduct): void
    {
        $orderProduct->product->decrement('quantity', $orderProduct->quantity);
    }
}
