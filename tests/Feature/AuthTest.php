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
    }
}