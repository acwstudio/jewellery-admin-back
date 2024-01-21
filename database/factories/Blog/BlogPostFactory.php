<?php

declare(strict_types=1);

namespace Database\Factories\Blog;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Blog\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakeName = fake()->jobTitle();
        return [
            'blog_category_id' => rand(1, 5),
            'image_id' => rand(1, 50),
            'preview_id' => rand(1, 50),
            'content' => fake()->text(),
            'slug' => SlugService::createSlug($this->model, 'slug', $fakeName),
            'title' => $fakeName,
            'status' => 'draft',
            'published_at' => now(),
            'is_main' => 1,
        ];
    }
}
