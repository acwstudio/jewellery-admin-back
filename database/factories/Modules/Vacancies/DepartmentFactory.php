<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Vacancies;

use App\Modules\Vacancies\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->name
        ];
    }
}
