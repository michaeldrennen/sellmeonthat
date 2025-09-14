<?php

namespace Database\Factories;

use App\Models\BusinessProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            // By default, every user is a consumer
            $consumerRole = Role::where('slug', 'consumer')->first();
            $user->roles()->attach($consumerRole);
        });
    }

    /**
     * Indicate that the user is a retailer.
     */
    public function retailer(): static
    {
        return $this->afterCreating(function (User $user) {
            $retailerRole = Role::where('slug', 'retailer')->first();
            $user->roles()->attach($retailerRole);

            // Create a business profile for the retailer
            BusinessProfile::factory()->create(['user_id' => $user->id]);
        });
    }
}