<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Blog;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Post;
use App\Packages\Enums\PostStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        return [
            'category_id' => $category->id,
            'slug' => fake()->slug(),
            'title' => fake()->jobTitle(),
            'content' => fake()->text(),
            'status' => fake()->randomElement(PostStatusEnum::cases()),
            'published_at' => Carbon::now()
        ];
    }
}
