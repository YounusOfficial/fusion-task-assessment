<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
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

    public function test_can_create_user()
    {
        $token = auth()->login($this->adminUser);

        $response = $this->postJson('/api/users', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'role' => 'Agent',
            'email' => 'jane.doe@example.com',
            'location_latitude' => 37.7749,
            'location_longitude' => -122.4194,
            'date_of_birth' => '1990-01-01',
            'timezone' => 'PST',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'first_name', 'last_name', 'role', 'email', 'location_latitude', 'location_longitude', 'date_of_birth', 'timezone', 'created_at', 'updated_at'
            ]);
    }

    public function test_can_get_all_users()
    {
        $token = auth()->login($this->adminUser);

        $response = $this->getJson('/api/users', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'first_name', 'last_name', 'role', 'email', 'location_latitude', 'location_longitude', 'date_of_birth', 'timezone', 'created_at', 'updated_at']
            ]);
    }

    public function test_can_get_single_user()
    {
        $token = auth()->login($this->adminUser);

        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}", [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'first_name', 'last_name', 'role', 'email', 'location_latitude', 'location_longitude', 'date_of_birth', 'timezone', 'created_at', 'updated_at'
            ]);
    }

    public function test_can_update_user()
    {
        $token = auth()->login($this->adminUser);

        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'role' => 'Supervisor',
            'email' => 'john.smith@example.com',
            'location_latitude' => 37.7749,
            'location_longitude' => -122.4194,
            'date_of_birth' => '1990-01-01',
            'timezone' => 'PST',
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'role' => 'Supervisor',
                'email' => 'john.smith@example.com',
                'location_latitude' => 37.7749,
                'location_longitude' => -122.4194,
                'date_of_birth' => '1990-01-01',
                'timezone' => 'PST',
            ]);
    }

    public function test_can_delete_user()
    {
        $token = auth()->login($this->adminUser);

        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}", [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
