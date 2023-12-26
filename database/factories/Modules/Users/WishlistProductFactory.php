<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Users;

use App\Modules\Catalog\Models\Product;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\WishlistProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<User>
 */
class WishlistProductFactory extends Factory
{
    protected $model = WishlistProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
        ];
    }
}
