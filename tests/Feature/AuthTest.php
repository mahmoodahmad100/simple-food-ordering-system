<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sends_invalid_data_when_login()
    {
        $response = $this->post('/api/v1/auth/login', [], ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }

    public function test_user_sends_valid_data_when_login()
    {
        $user = User::factory()->create();

        $data = [
            'email'    => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/login', $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'is_success',
            'status_code',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'token'
            ]
        ]);
    }

    public function test_user_sends_wrong_password_when_login()
    {
        $user = User::factory()->create();

        $data = [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->post('/api/v1/auth/login', $data, ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson([
            'is_success' => false,
            'status_code' => 422,
        ]);
    }

    public function test_user_sends_nonexistent_email_when_login()
    {
        $data = [
            'email'    => 'nonexistent@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/login', $data, ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson([
            'is_success' => false,
            'status_code' => 422,
        ]);
    }

    public function test_user_sends_invalid_email_format_when_login()
    {
        $data = [
            'email'    => 'invalid-email',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/login', $data, ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson([
            'is_success' => false,
            'status_code' => 422,
        ]);
    }
}