<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'role' => 'Agent',
            'email' => $this->faker->unique()->safeEmail,
            'location_latitude' => $this->faker->latitude,
            'location_longitude' => $this->faker->longitude,
            'date_of_birth' => $this->faker->date,
            'timezone' => $this->faker->timezone,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
