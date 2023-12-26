<?php

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\CategorySlugAlias;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategorySlugAliasFactory extends Factory
{
    protected $model = CategorySlugAlias::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug' => fake()->slug(rand(2,4)),
        ];
    }
}
