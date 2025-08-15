<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $result = $this->authService->login($credentials);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
        $this->assertNotNull($result->token);
    }

    public function test_failed_login_throws_exception()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid credentials');

        $this->authService->login($credentials);
    }

    public function test_login_with_nonexistent_user_throws_exception()
    {
        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid credentials');

        $this->authService->login($credentials);
    }
}
