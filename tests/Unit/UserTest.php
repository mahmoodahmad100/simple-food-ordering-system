<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_orders()
    {
        $user = User::factory()->create();
        $order1 = Order::create(['user_id' => $user->id]);
        $order2 = Order::create(['user_id' => $user->id]);

        $this->assertCount(2, $user->orders);
        $this->assertTrue($user->orders->contains($order1));
        $this->assertTrue($user->orders->contains($order2));
    }

    public function test_user_fillable_attributes()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('test', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    public function test_user_casts()
    {
        $user = User::factory()->create();
        $casts = $user->getCasts();

        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    public function test_user_can_create_api_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->assertNotNull($token);
        $this->assertNotNull($token->plainTextToken);
    }
}
