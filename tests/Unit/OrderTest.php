<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_has_many_products()
    {
        $user = User::factory()->create();
        $order = Order::create(['user_id' => $user->id]);
        $product1 = Product::create(['name' => 'Product 1']);
        $product2 = Product::create(['name' => 'Product 2']);

        $order->products()->attach($product1->id, ['quantity' => 1]);
        $order->products()->attach($product2->id, ['quantity' => 2]);

        $this->assertCount(2, $order->products);
        $this->assertEquals(1, $order->products[0]->pivot->quantity);
        $this->assertEquals(2, $order->products[1]->pivot->quantity);
    }
}