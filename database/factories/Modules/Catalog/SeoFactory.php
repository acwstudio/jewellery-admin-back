<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Seo;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoFactory extends Factory
{
    protected $model = Seo::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'url' => $this->faker->url(),
            'filters' => ['in_stock' => true],
            'h1' => $this->faker->text(10),
        ];
    }
}
