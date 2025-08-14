<?php

namespace App\Services;

use App\Models\Order;

class IngredientService
{
    /**
     * @param Order $order
     * @return void
     */
    public function updateStock(Order $order): void
    {
        foreach ($order->products as $product) {
            foreach ($product->ingredients as $ingredient) {
                $ingredient->current_amount -= $product->pivot->quantity * $ingredient->pivot->amount;

                if ($ingredient->current_amount < 0) {
                    abort(422, "Not enough ingredients for {$product->name}({$ingredient->name} is out of stock)");
                }

                if ($ingredient->isDirty('current_amount')) {
                    $threshold = config('app.stock_threshold_percentage');
                    if (((($ingredient->current_amount / $ingredient->total_amount) * 100) < $threshold) &&
                        !$ingredient->is_low_amount_alert_email_sent) {
                        /**
                         * @TODO: Send email to merchant
                         */
                        $ingredient->is_low_amount_alert_email_sent = true;
                    }
                }

                $ingredient->save();
            }
        }
    }
}