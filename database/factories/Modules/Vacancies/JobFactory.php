<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Vacancies;

use App\Modules\Vacancies\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->title,
            'salary' => fake()->numberBetween(10000, 30000),
            'city' => fake()->city,
            'experience' => fake()->title,
            'description' => fake()->text,
            'slug' => fake()->slug
        ];
    }

    public function withDepartmentId(int $departmentId): self
    {
        return $this->state(
            function () use ($departmentId) {
                return ['department_id' => $departmentId];
            }
        );
    }
}
