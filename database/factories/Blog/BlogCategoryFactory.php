<?php

declare(strict_types=1);

namespace Database\Factories\Blog;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory <BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    protected $model = BlogCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakeName = fake()->jobTitle();
        return [
            'slug' => SlugService::createSlug($this->model, 'slug', $fakeName),
            'name' => $fakeName
        ];
    }
}
