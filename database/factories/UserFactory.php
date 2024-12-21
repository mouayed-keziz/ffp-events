<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'verified_at' => null,
        ]);
    }

    /**
     * Assign super admin role to the user
     */
    public function superAdmin(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('super_admin');
        });
    }

    /**
     * Assign admin role to the user
     */
    public function admin(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('admin');
        });
    }

    /**
     * Assign exhibitor role to the user
     */
    public function exhibitor(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('exhibitor');
        });
    }

    public function visitor(): static
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('visitor');
        });
    }
}
