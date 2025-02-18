<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $payload = [
            'name' => 'Jean',
            'email' => 'jean@email.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email']
        ]);
    }

    public function test_user_cannot_register_with_invalid_data(): void
    {
        $payload = [
            'name' => '',
            'email' => 'emailinvalido',
            'password' => '123'
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678')
        ]);

        $payload = [
            'email' => $user->email,
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $payload = [
            'email' => 'teste@email.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Não autorizado']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678')
        ]);


        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $accessToken = $loginResponse->json('access_token');

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $accessToken,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout feito com sucesso']);
    }

    public function test_user_cannot_logout_without_login(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Erro ao deslogar']);
    }
}
