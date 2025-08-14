<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Services\IngredientService;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param OrderRepository $orderRepository
     * @param IngredientService $ingredientService
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private IngredientService $ingredientService
    )
    {
        //...
    }

    /**
     * @param array $products
     * @return array
     */
    public function getFormattedProducts(array $products): array
    {
        $result = [];

        foreach ($products as $product) {
            $result[$product['product_id']] = ['quantity' => $product['quantity']];
        }

        return $result;
    }

    /**
     * This method is used to create an order and update the ingredients stock.
     * Please note that it is wrapped in a transaction to ensure that:
     * 1. the order is created and the ingredients stock is updated.
     * 2. if the ingredients stock is not enough, the order is not created.
     * so the order will not be created if the ingredients stock is not enough.
     * and that's why the code below is wrapped in a transaction to ensure consistency.
     * 
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = $this->orderRepository->create($data);
            $order->products()->sync($this->getFormattedProducts($data['products']));
            $this->ingredientService->updateStock($order);
            return $order;
        });
    }
}