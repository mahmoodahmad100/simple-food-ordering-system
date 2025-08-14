<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\OrderPostRequest;

class OrderController extends Controller
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(private OrderService $orderService)
    {
        //...
    }

    /**
     * @param OrderPostRequest $request
     * @return JsonResponse
     */
    public function store(OrderPostRequest $request)
    {
        return $this->getResponse(
            $this->orderService->create(array_merge($request->validated(), ['user_id' => auth()->user()->id])),
            'Order created successfully',
            Response::HTTP_CREATED
        );
    }
}