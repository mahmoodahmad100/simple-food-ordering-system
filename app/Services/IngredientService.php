<?php

namespace App\Services;

use App\Models\Order;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\AppException;

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
                    throw new AppException(
                        "Not enough ingredients for {$product->name}({$ingredient->name} is out of stock)",
                        422
                    );
                }

                if ($ingredient->isDirty('current_amount')) {
                    $threshold = config('app.stock_threshold_percentage');
                    if (((($ingredient->current_amount / $ingredient->total_amount) * 100) < $threshold) &&
                        !$ingredient->is_low_amount_alert_email_sent) {
                        Mail::to('merchant@example.com')->queue(new LowStockAlert($ingredient));
                        $ingredient->is_low_amount_alert_email_sent = true;
                    }
                }

                $ingredient->save();
            }
        }
    }
}