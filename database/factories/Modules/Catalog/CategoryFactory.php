<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'title' => fake()->text(50),
            'h1' => fake()->text(50),
            'description' => fake()->text(50),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords'  => fake()->text(50),
            'external_id' => fake()->sha1,
            'slug' => fake()->slug()
        ];
    }

    public function withAttributes(array $attributes): self
    {
        return $this->state(function () use ($attributes) {
            return $attributes;
        });
    }
}
