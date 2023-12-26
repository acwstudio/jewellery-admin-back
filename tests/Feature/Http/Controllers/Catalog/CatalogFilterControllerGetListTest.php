<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog;

use App\Modules\Catalog\Enums\FeatureDynamicTypeEnum;
use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Providers\PriceProductFilterProvider;
use App\Modules\Catalog\Providers\SizeProductFilterProvider;
use App\Packages\Enums\FilterTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Money\Money;
use Tests\TestCase;

class CatalogFilterControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/filter';

    public function testSuccessful()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);
    }

    public function testSuccessfulByPrice()
    {
        $productOffers = ProductOffer::factory(5)->create();
        foreach ($productOffers as $productOffer) {
            ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('min', $filter['settings']);
            self::assertArrayHasKey('max', $filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === PriceProductFilterProvider::FILTER_NAME) {
                self::assertEquals(FilterTypeEnum::NUM->value, $filter['type']);
                self::assertNotNull($filter['settings']['min']);
                self::assertNotNull($filter['settings']['max']);
                self::assertEmpty($filter['settings']['options']);
            }
        }
    }

    public function testSuccessfulByPriceIsActive()
    {
        $productOffers = ProductOffer::factory(5)->create();
        foreach ($productOffers as $key => $productOffer) {
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $productOffer->getKey(),
                'price' => Money::RUB((1000 + $key) * 100)
            ]);
        }

        /** @var ProductOffer $noActiveProductOffer */
        $noActiveProductOffer = ProductOffer::factory()->create();
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $noActiveProductOffer->getKey(),
            'price' => Money::RUB(5000 * 100)
        ]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            if ($filter['name'] === PriceProductFilterProvider::FILTER_NAME) {
                self::assertEquals(FilterTypeEnum::NUM->value, $filter['type']);
                self::assertNotNull($filter['settings']['min']);
                self::assertEquals(1000, $filter['settings']['min']);
                self::assertNotNull($filter['settings']['max']);
                self::assertEquals(5000, $filter['settings']['max']);
            }
        }

        $noActiveProductOffer->product->update(['is_active' => false]);

        Cache::flush();
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            if ($filter['name'] === PriceProductFilterProvider::FILTER_NAME) {
                self::assertEquals(FilterTypeEnum::NUM->value, $filter['type']);
                self::assertNotNull($filter['settings']['min']);
                self::assertEquals(1000, $filter['settings']['min']);
                self::assertNotNull($filter['settings']['max']);
                self::assertEquals(1004, $filter['settings']['max']);
            }
        }
    }

    public function testSuccessfulByMetal()
    {
        $products = Product::factory(4)->create(['setFull' => true]);

        $metalType = FeatureTypeEnum::METAL->value;
        $metals = new Collection([
            Feature::factory()->create(['type' => $metalType, 'value' => 'Золото']),
            Feature::factory()->create(['type' => $metalType, 'value' => 'Серебро'])
        ]);

        /** @var Product $product */
        foreach ($products as $product) {
            ProductFeature::factory()->create(['product_id' => $product, 'feature_id' => $metals->random()]);
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('min', $filter['settings']);
            self::assertArrayHasKey('max', $filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === "feature[{$metalType}]") {
                self::assertEquals(FilterTypeEnum::SELECT->value, $filter['type']);
                self::assertEmpty($filter['settings']['min']);
                self::assertEmpty($filter['settings']['max']);
                self::assertNotEmpty($filter['settings']['options']);
                self::assertIsArray($filter['settings']['options']);
                foreach ($filter['settings']['options'] as $option) {
                    self::assertArrayHasKey('name', $option);
                    self::assertArrayHasKey('value', $option);
                    self::assertArrayHasKey('slug', $option);
                    self::assertArrayHasKey('count', $option);
                }
            }
        }
    }

    public function testSuccessfulByDynamic()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $dynamicType = FeatureTypeEnum::DYNAMIC->value;
        $dynamicValue = FeatureDynamicTypeEnum::WEIGHT->getLabel();
        $dynamicValueType = FeatureDynamicTypeEnum::WEIGHT->value;
        $dynamic = Feature::factory()->create(['type' => $dynamicType, 'value' => $dynamicValue]);

        /** @var Product $product */
        foreach ($products as $product) {
            ProductFeature::factory()->create([
                'product_id' => $product,
                'feature_id' => $dynamic,
                'value' => (string) fake()->randomFloat(null, 0, 10)
            ]);
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('min', $filter['settings']);
            self::assertArrayHasKey('max', $filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === "feature[{$dynamicType}][{$dynamicValueType}]") {
                self::assertEquals(FilterTypeEnum::NUM->value, $filter['type']);
                self::assertNotNull($filter['settings']['min']);
                self::assertNotNull($filter['settings']['max']);
                self::assertEmpty($filter['settings']['options']);
            }
        }
    }

    public function testSuccessfulByMetalIsActive()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $notActiveProducts = Product::factory(2)->create(['is_active' => false, 'setFull' => true]);

        $metalType = FeatureTypeEnum::METAL->value;
        $metal = Feature::factory()->create(['type' => $metalType, 'value' => 'Золото']);

        /** @var Product $product */
        foreach ($products as $product) {
            ProductFeature::factory()->create(['product_id' => $product, 'feature_id' => $metal]);
        }

        /** @var Product $product */
        foreach ($notActiveProducts as $product) {
            ProductFeature::factory()->create(['product_id' => $product, 'feature_id' => $metal]);
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === "feature[{$metalType}]") {
                self::assertEquals(FilterTypeEnum::SELECT->value, $filter['type']);
                self::assertEmpty($filter['settings']['min']);
                self::assertEmpty($filter['settings']['max']);
                self::assertNotEmpty($filter['settings']['options']);
                self::assertIsArray($filter['settings']['options']);
                foreach ($filter['settings']['options'] as $option) {
                    self::assertArrayHasKey('name', $option);
                    self::assertArrayHasKey('value', $option);
                    self::assertArrayHasKey('slug', $option);
                    self::assertArrayHasKey('count', $option);
                    if ($option['value'] === "Золото") {
                        self::assertEquals(3, $option['count']);
                    }
                }
            }
        }
    }

    public function testSuccessfulBySize()
    {
        $category = Category::factory()->create();

        $data = [
            'applied_filter' => [
                'category' => $category->getKey()
            ]
        ];

        $products = Product::factory(5)->create(['setFull' => true]);

        /** @var Collection<Product> $categoryProducts */
        $categoryProducts = $products->random(2);
        foreach ($categoryProducts as $product) {
            $product->categories()->sync([$category->getKey()]);
        }

        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('min', $filter['settings']);
            self::assertArrayHasKey('max', $filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === SizeProductFilterProvider::FILTER_NAME) {
                self::assertEquals(FilterTypeEnum::BUTTON->value, $filter['type']);
                self::assertIsArray($filter['settings']['options']);
            }
        }
    }

    public function testSuccessfulBySizeBySlug()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $data = [
            'applied_filter' => [
                'category' => $category->slug
            ]
        ];

        $products = Product::factory(5)->create(['setFull' => true]);

        /** @var Collection<Product> $categoryProducts */
        $categoryProducts = $products->random(2);
        foreach ($categoryProducts as $product) {
            $product->categories()->sync([$category->getKey()]);
        }

        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('filters', $content);
        self::assertIsArray($content['filters']);

        foreach ($content['filters'] as $filter) {
            self::assertArrayHasKey('name', $filter);
            self::assertArrayHasKey('type', $filter);
            self::assertArrayHasKey('settings', $filter);
            self::assertIsArray($filter['settings']);
            self::assertArrayHasKey('min', $filter['settings']);
            self::assertArrayHasKey('max', $filter['settings']);
            self::assertArrayHasKey('options', $filter['settings']);

            if ($filter['name'] === SizeProductFilterProvider::FILTER_NAME) {
                self::assertEquals(FilterTypeEnum::BUTTON->value, $filter['type']);
                self::assertIsArray($filter['settings']['options']);
            }
        }
    }
}
