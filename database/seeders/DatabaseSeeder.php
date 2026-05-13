<?php

namespace Database\Seeders;

use App\Enums\DifficultyEnum;
use App\Enums\StatusEnum;
use App\Models\Concept;
use App\Models\Domain;
use App\Models\GeneratedQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Abderahmane Merradou',
            'email' => 'test@example.com',
        ]);

        $laravelDomain = Domain::factory()->create([
            'user_id' => $user->id,
            'name' => 'Laravel',
            'color' => '#FF5733',
        ]);

        $mysqlDomain = Domain::factory()->create([
            'user_id' => $user->id,
            'name' => 'MySQL',
            'color' => '#3357FF',
        ]);

        $oopDomain = Domain::factory()->create([
            'user_id' => $user->id,
            'name' => 'PHP OOP',
            'color' => '#33FF57',
        ]);

        $apiDomain = Domain::factory()->create([
            'user_id' => $user->id,
            'name' => 'REST API',
            'color' => '#F5FF33',
        ]);

        $concepts = [
            'Eloquent N+1 Problem' => [
                'difficulty' => DifficultyEnum::MID,
                'status' => StatusEnum::TO_REVIEW,
                'explanation' => 'The N+1 query problem occurs when Eloquent loads a collection of models and then accesses a lazy-loaded relationship for each model, resulting in 1 query to load the models plus N additional queries to load the relationship for each model.',
            ],
            'Laravel Service Container' => [
                'difficulty' => DifficultyEnum::SENIOR,
                'status' => StatusEnum::IN_PROGRESS,
                'explanation' => 'The Service Container is a powerful tool for managing class dependencies and performing dependency injection. It automatically resolve dependencies and handle lifecycle management of objects.',
            ],
            'Middleware' => [
                'difficulty' => DifficultyEnum::JUNIOR,
                'status' => StatusEnum::MASTERED,
                'explanation' => 'Middleware provide a mechanism for filtering HTTP requests entering your application. They can modify requests, block them, or pass them to the next middleware.',
            ],
            'Database Indexing' => [
                'difficulty' => DifficultyEnum::MID,
                'status' => StatusEnum::TO_REVIEW,
                'explanation' => 'Database indexes are data structures that improve the speed of data retrieval operations on a database table at the cost of additional writes and storage space.',
            ],
            'Query Optimization' => [
                'difficulty' => DifficultyEnum::SENIOR,
                'status' => StatusEnum::IN_PROGRESS,
                'explanation' => 'Query optimization involves improving the performance of SQL queries by analyzing execution plans, adding proper indexes, and rewriting queries to be more efficient.',
            ],
            'Polymorphism' => [
                'difficulty' => DifficultyEnum::MID,
                'status' => StatusEnum::MASTERED,
                'explanation' => 'Polymorphism allows objects of different classes to be treated as objects of a common superclass. It enables a single interface to represent different underlying forms.',
            ],
            'RESTful API Design' => [
                'difficulty' => DifficultyEnum::MID,
                'status' => StatusEnum::TO_REVIEW,
                'explanation' => 'RESTful API design follows REST principles using HTTP methods (GET, POST, PUT, DELETE) to perform CRUD operations on resources identified by URLs.',
            ],
            'Authentication with Sanctum' => [
                'difficulty' => DifficultyEnum::MID,
                'status' => StatusEnum::IN_PROGRESS,
                'explanation' => 'Laravel Sanctum provides a simple authentication system for SPAs, mobile applications, and token-based APIs. It uses API tokens for authentication.',
            ],
        ];

        foreach ($concepts as $title => $data) {
            $domain = match (true) {
                str_contains(strtolower($title), 'eloquent') || str_contains(strtolower($title), 'laravel') || str_contains(strtolower($title), 'middleware') || str_contains(strtolower($title), 'sanctum') => $laravelDomain,
                str_contains(strtolower($title), 'database') || str_contains(strtolower($title), 'query') => $mysqlDomain,
                str_contains(strtolower($title), 'polymorphism') || str_contains(strtolower($title), 'oop') => $oopDomain,
                str_contains(strtolower($title), 'api') || str_contains(strtolower($title), 'rest') => $apiDomain,
                default => $laravelDomain,
            };

            $concept = Concept::factory()->create([
                'user_id' => $user->id,
                'domain_id' => $domain->id,
                'title' => $title,
                'explanation' => $data['explanation'],
                'difficulty_level' => $data['difficulty'],
                'status' => $data['status'],
            ]);

            if (in_array($data['status'], [StatusEnum::IN_PROGRESS, StatusEnum::MASTERED])) {
                GeneratedQuestion::factory()->create([
                    'concept_id' => $concept->id,
                    'question' => json_encode([
                        "What is {$title} and when would you use it?",
                        "Explain the advantages and disadvantages of {$title}.",
                        "How would you implement {$title} in a Laravel application?",
                        "What are common pitfalls when working with {$title}?",
                        "How does {$title} relate to other Laravel concepts?",
                    ]),
                ]);
            }
        }
    }
}
