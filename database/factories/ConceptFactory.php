<?php

namespace Database\Factories;

use App\Enums\DifficultyEnum;
use App\Enums\StatusEnum;
use App\Models\Concept;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConceptFactory extends Factory
{
    protected $model = Concept::class;

    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'explanation' => fake()->paragraphs(3, true),
            'difficulty_level' => fake()->randomElement(DifficultyEnum::cases()),
            'status' => fake()->randomElement(StatusEnum::cases()),
        ];
    }
}