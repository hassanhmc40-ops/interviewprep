<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33F5', '#F5FF33', '#33FFF5'];

        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->words(2, true),
            'color' => fake()->randomElement($colors),
        ];
    }
}