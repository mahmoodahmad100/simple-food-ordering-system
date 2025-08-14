<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product1 = Product::create(['name' => 'Product 1']);
        $product2 = Product::create(['name' => 'Product 2']);

        $data = [
            'products' => [
                ['product_id' => $product1->id, 'quantity' => 1],
                ['product_id' => $product2->id, 'quantity' => 2],
            ],
        ];

        $response = $this->post('/api/v1/orders', $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);
    }

    public function test_sending_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/api/v1/orders', [], ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }

    public function test_sending_email_if_ingredient_is_below_threshold_percentage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::create(['name' => 'Product 1']);
        $ingredient = Ingredient::create(['name' => 'Ingredient 1', 'total_amount' => 100, 'current_amount' => 100]);
        $product->ingredients()->attach($ingredient->id, ['amount' => 10]);

        // Simulate order creation that reduces ingredient amount below threshold
        $data = [
            'products' => [
                ['product_id' => $product->id, 'quantity' => 8],
            ],
        ];

        Mail::fake();

        $response = $this->post('/api/v1/orders', $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);

        // Assert that an email was queued for sending
        Mail::assertQueued(LowStockAlert::class, function ($mail) use ($ingredient) {
            return $mail->ingredient->id === $ingredient->id;
        });

        // Assert that the ingredient has the is_low_amount_alert_email_sent attribute set to true
        $this->assertTrue((bool)$ingredient->fresh()->is_low_amount_alert_email_sent);

        // Assert that the current amount of the ingredient is updated
        $this->assertEquals(20, $ingredient->fresh()->current_amount);
    }

    public function test_sending_quantity_bigger_than_the_ingredient_amount_in_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::create(['name' => 'Product 1']);
        $ingredient = Ingredient::create(['name' => 'Ingredient 1', 'total_amount' => 100, 'current_amount' => 100]);
        $product->ingredients()->attach($ingredient->id, ['amount' => 10]);

        $data = [
            'products' => [
                ['product_id' => $product->id, 'quantity' => 11],
            ],
        ];

        $response = $this->post('/api/v1/orders', $data, ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }
}