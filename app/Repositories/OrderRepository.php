<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * @param Order $model
     */
    public function __construct(private Order $model)
    {

    }

    /**
     * create new order
     * 
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return $this->model->create($data);
    }
}