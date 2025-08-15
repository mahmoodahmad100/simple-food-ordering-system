<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_many_ingredients()
    {
        $product = Product::create(['name' => 'Burger']);

        $ingredient1 = Ingredient::create([
            'name' => 'Beef',
            'total_amount' => 1000,
            'current_amount' => 500,
        ]);
        $ingredient2 = Ingredient::create([
            'name' => 'Lettuce',
            'total_amount' => 100,
            'current_amount' => 50,
        ]);

        $product->ingredients()->attach($ingredient1->id, ['amount' => 100]);
        $product->ingredients()->attach($ingredient2->id, ['amount' => 10]);

        $this->assertCount(2, $product->ingredients);
        $this->assertTrue($product->ingredients->contains($ingredient1));
        $this->assertTrue($product->ingredients->contains($ingredient2));
        $this->assertEquals(100, $product->ingredients->first()->pivot->amount);
    }

    public function test_product_belongs_to_many_orders()
    {
        $product = Product::create(['name' => 'Pizza']);
        $user = User::factory()->create();
        $order = Order::create(['user_id' => $user->id]);

        $product->orders()->attach($order->id, ['quantity' => 2]);

        $this->assertCount(1, $product->orders);
        $this->assertTrue($product->orders->contains($order));
        $this->assertEquals(2, $product->orders->first()->pivot->quantity);
    }

    public function test_product_fillable_attributes()
    {
        $product = Product::create(['name' => 'Salad']);

        $this->assertEquals('Salad', $product->name);
    }
}
