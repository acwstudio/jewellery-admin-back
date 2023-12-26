<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Rules;

use App\Modules\Rules\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Rules\Models\Rule>
 */
class RuleFactory extends Factory
{
    protected $model = Rule::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->title,
            'description' => fake()->title,
            'country' => fake()->title,
            'date_start' => fake()->date,
            'date_finish' => fake()->date,
            'slug' => fake()->slug
        ];
    }
}
