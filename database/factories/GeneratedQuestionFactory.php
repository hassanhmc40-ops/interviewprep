<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneratedQuestionFactory extends Factory
{
    protected $model = GeneratedQuestion::class;

    public function definition(): array
    {
        return [
            'concept_id' => Concept::factory(),
            'question' => json_encode([
                fake()->sentence() . '?',
                fake()->sentence() . '?',
                fake()->sentence() . '?',
                fake()->sentence() . '?',
                fake()->sentence() . '?',
            ]),
            'generated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}