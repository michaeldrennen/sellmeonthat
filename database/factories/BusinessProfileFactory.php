<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_name' => fake()->company(),
            'description' => fake()->paragraph(),
            'website' => fake()->domainName(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}