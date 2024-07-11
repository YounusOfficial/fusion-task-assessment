<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'Admin',
            'email' => 'admin@example.com',
            'location_latitude' => 37.7749,
            'location_longitude' => -122.4194,
            'date_of_birth' => '1980-01-01',
            'timezone' => 'PST',
            'password' => Hash::make('password'),
        ]);
    }


    public function test_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->adminUser->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_logout()
    {
        $token = auth()->login($this->adminUser);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }

    public function test_get_authenticated_user()
    {
        $token = auth()->login($this->adminUser);

        $response = $this->getJson('/api/me', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'first_name', 'last_name', 'role', 'email', 'location_latitude', 'location_longitude', 'date_of_birth', 'timezone', 'created_at', 'updated_at']);
    }
}
