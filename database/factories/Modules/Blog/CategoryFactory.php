<?php

namespace Database\Factories\Modules\Blog;

use App\Modules\Blog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug' => fake()->slug(),
            'name' => fake()->jobTitle(),
            'position' => fake()->randomNumber()
        ];
    }
}
