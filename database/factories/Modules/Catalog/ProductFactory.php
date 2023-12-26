<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\LiquidityEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->sha1,
            'sku' => (string)$this->faker->unique()->numberBetween(1000),
            'name' => $this->faker->text(50),
            'summary' => $this->faker->text(50),
            'description' => $this->faker->text(50),
            'manufacture_country' => $this->faker->text(50),
            'rank' => $this->faker->randomDigit(),
            'catalog_number' => $this->faker->text(10),
            'supplier' => $this->faker->text(10),
            'liquidity' => $this->faker->randomElement(LiquidityEnum::cases())->value,
            'stamp' => $this->faker->randomFloat(),
            'meta_title' => $this->faker->text(50),
            'meta_description' => $this->faker->text(50),
            'meta_keywords' => $this->faker->text(50),
            'is_active' => true,
            'is_drop_shipping' => $this->faker->boolean,
            'popularity' => $this->faker->randomDigit(),
            'slug' => $this->faker->slug()
        ];
    }

    public function create($attributes = [], ?Model $parent = null): Collection|Product
    {
        $setFull = false;
        if (!empty($attributes['setFull'])) {
            unset($attributes['setFull']);
            $setFull = true;
        }

        $models = parent::create($attributes, $parent);

        if ($setFull) {
            $this->setFullData($models);
        }

        return $models;
    }

    private function setFullData(Collection|Product $models): void
    {
        if ($models instanceof Product) {
            $this->createProductAllData($models);
            $models->updateInScout();
            return;
        }

        /** @var Product $model */
        foreach ($models as $model) {
            $this->createProductAllData($model);
            $model->updateInScout();
        }
    }

    private function createProductAllData(Product $product): void
    {
        $productOffer = ProductOffer::factory()->create(['product_id' => $product->getKey()]);
        ProductOfferStock::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'count' => 5,
            'is_current' => true
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::REGULAR,
            'is_active' => true
        ]);

        ProductImageUrl::factory()->create(['product_id' => $product->getKey(), 'is_main' => true]);
        ProductImageUrl::factory()->create(['product_id' => $product->getKey(), 'is_main' => false]);
    }
}
