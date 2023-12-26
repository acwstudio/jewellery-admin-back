<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\WishlistProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Money\Money;
use Tests\TestCase;

class ProductControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product';

    public function testSuccessful()
    {
        Product::factory(3)->create(['setFull' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulBySale()
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        /** @var Product $product */
        foreach ($products as $product) {
            $offer = $product->productOffers->first();
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $offer->getKey(),
                'type' => OfferPriceTypeEnum::SALE,
                'price' => Money::RUB(100000)
            ]);
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('price_old', $item);
            self::assertNotEmpty($item['price_old']);
        }
    }

    public function testSuccessfulOnlyFull()
    {
        Product::factory(3)->create();
        Product::factory(3)->create(['setFull' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulPagination()
    {
        Product::factory(5)->create(['setFull' => true]);

        $query = [
            'pagination' => [
                'page' => 1,
                'per_page' => 3
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulEmptyItems()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testSuccessfulSortByPopularityAndPagination()
    {
        Product::factory(3)->create(['setFull' => true]);
        Product::factory(3)->create(['popularity' => null, 'setFull' => true]);

        $query = [
            'sort_by' => ProductSortColumnEnum::POPULARITY->value,
            'sort_order' => SortOrderEnum::DESC->value,
            'pagination' => [
                'page' => 1,
                'per_page' => 4
            ]
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertNotEmpty($content['items']);

        $ids = [];
        foreach ($content['items'] as $key => $item) {
            self::assertArrayHasKey('id', $item);
            $ids[$key] = $item['id'];
        }

        $query['pagination']['per_page'] = 5;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertNotEmpty($content['items']);

        foreach ($content['items'] as $key => $item) {
            self::assertArrayHasKey('id', $item);
            if (isset($ids[$key])) {
                self::assertEquals($ids[$key], $item['id']);
            }
        }
    }

    public function testSuccessfulFilterByInStock()
    {
        /** @var Collection<Product> $products */
        $products = Product::factory(6)->create(['setFull' => true]);

        /** @var Collection<Product> $zeroProducts */
        $zeroProducts = $products->random(2);
        foreach ($zeroProducts as $product) {
            /** @var Collection<ProductOffer> $productOffers */
            $productOffers = $product->productOffers()->getQuery()->get();
            foreach ($productOffers as $productOffer) {
                $productOffer->productOfferStocks()->getQuery()->update(['count' => 0]);
            }
        }

        $response = $this->get(self::METHOD . '?filter[in_stock]=1');
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);

        $response = $this->get(self::METHOD . '?filter[in_stock]=0');
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulSortByCreatedAt()
    {
        $products = Product::factory(5)->create(['setFull' => true]);

        foreach ($products as $key => $product) {
            $product->setCreatedAt(Carbon::now()->addDays($key));
            $product->save();
        }

        $query = [
            'sort_by' => ProductSortColumnEnum::CREATED_AT->value,
            'sort_order' => SortOrderEnum::DESC->value
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);

        $id = null;
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            if ($id !== null) {
                self::assertLessThanOrEqual($id, $item['id']);
            }
            $id = $item['id'];
        }

        $query['sort_order'] = SortOrderEnum::ASC->value;

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);

        $id = null;
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            if ($id !== null) {
                self::assertGreaterThanOrEqual($id, $item['id']);
            }
            $id = $item['id'];
        }
    }

    public function testSuccessfulFilterByCategoryId()
    {
        /** @var Category $categoryOne */
        $categoryOne = Category::factory()->create();

        /** @var Category $categoryTwo */
        $categoryTwo = Category::factory()->create();

        Product::factory()->create(['setFull' => true]);
        Product::factory(2)->afterCreating(
        /** @phpstan-ignore-next-line */
            fn (Product $product) => $product->categories()->attach($categoryOne)
        )->create(['setFull' => true]);
        Product::factory(3)->afterCreating(
        /** @phpstan-ignore-next-line */
            fn (Product $product) => $product->categories()->attach($categoryTwo)
        )->create(['setFull' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(6, $content['items']);

        $query['filter']['category'] = $categoryOne->getKey();
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $query['filter']['category'] = $categoryTwo->getKey();
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $query['filter']['category'] = $categoryOne->getKey() . ',' . $categoryTwo->getKey();
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulFilterByCategorySlug()
    {
        /** @var Category $categoryOne */
        $categoryOne = Category::factory()->create();

        /** @var Category $categoryTwo */
        $categoryTwo = Category::factory()->create();

        Product::factory()->create(['setFull' => true]);
        Product::factory(2)->afterCreating(
        /** @phpstan-ignore-next-line */
            fn (Product $product) => $product->categories()->attach($categoryOne)
        )->create(['setFull' => true]);
        Product::factory(3)->afterCreating(
        /** @phpstan-ignore-next-line */
            fn (Product $product) => $product->categories()->attach($categoryTwo)
        )->create(['setFull' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(6, $content['items']);

        $query['filter']['category'] = $categoryOne->slug;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $query['filter']['category'] = $categoryTwo->slug;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $query['filter']['category'] = $categoryOne->slug . ',' . $categoryTwo->slug;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulFilterByBrands()
    {
        /** @var Brand $brandOne */
        $brandOne = Brand::factory()->create();

        /** @var Brand $brandTwo */
        $brandTwo = Brand::factory()->create();

        Product::factory(2)->create(['brand_id' => $brandOne, 'setFull' => true]);
        Product::factory(3)->create(['brand_id' => $brandTwo, 'setFull' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);

        $response = $this->get(self::METHOD . '?filter[brands][]=' . $brandOne->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $response = $this->get(self::METHOD . '?filter[brands][]=' . $brandTwo->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulFilterByIds()
    {
        $products = Product::factory(5)->create(['setFull' => true])->random(2);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);

        $query = [
            'filter' => [
                'ids' => []
            ]
        ];
        /** @var Product $product */
        foreach ($products as $product) {
            $query['filter']['ids'][] = $product->getKey();
        }

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulByWishlist()
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var Product $product */
        $product = $products->random();
        WishlistProduct::factory()->create(['user_id' => $user, 'product_id' => $product->id]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('on_wishlist', $item);

            if ($item['id'] === $product->id) {
                self::assertTrue($item['on_wishlist']);
            }
        }
    }

    public function testSuccessfulFilterByFeatures()
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        $featureMetalZoloto = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL,
            'value' => 'Золото',
            'slug' => 'iz_zolota'
        ]);

        $featureMetalColorBeloe = Feature::factory()->create([
            'type' => FeatureTypeEnum::METAL_COLOR,
            'value' => 'Белое',
            'slug' => 'belogo_cveta'
        ]);

        /** @var Product $product */
        $product = $products->random();
        ProductFeature::factory()->create([
            'product_id' => $product,
            'feature_id' => $featureMetalZoloto
        ]);
        ProductFeature::factory()->create([
            'product_id' => $product,
            'feature_id' => $featureMetalColorBeloe->getKey()
        ]);
        $product->updateInScout();

        $query = [
            'filter' => [
                'feature' => [
                    'metal' => $featureMetalZoloto->getKey(),
                    'metal_color' => $featureMetalColorBeloe->getKey(),
                ]
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals($product->getKey(), $item['id']);
        }
    }

    public function testSuccessfulFilterByFeatureDynamic()
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        $featureDynamic = Feature::factory()->create([
            'type' => FeatureTypeEnum::DYNAMIC,
            'value' => 'Вес',
            'slug' => 'dynamic_ves'
        ]);

        /** @var Product $product */
        $product = $products->random();
        ProductFeature::factory()->create([
            'product_id' => $product,
            'feature_id' => $featureDynamic,
            'value' => '1.2'
        ]);

        ProductFeature::factory()->create([
            'feature_id' => $featureDynamic,
            'value' => '8*9'
        ]);

        $query = [
            'filter' => [
                'feature' => [
                    'dynamic' => [
                        'weight' => [
                            'min' => 1,
                            'max' => 2
                        ]
                    ],
                ]
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals($product->getKey(), $item['id']);
        }
    }

    public function testSuccessfulByLive()
    {
        $productOfferPriceOne = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::LIVE, 10035, $productOfferPriceOne);
        $this->addLive($productOfferPriceOne->productOffer->product);

        $productOfferPriceTwo = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 30020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::LIVE, 20035, $productOfferPriceTwo);

        $productOfferPriceThree = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 40020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::LIVE, 30035, $productOfferPriceThree);
        $this->addLive($productOfferPriceThree->productOffer->product);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulFilterByHasImage()
    {
        /** @var Collection<Product> $products */
        $products = Product::factory(10)->create(['setFull' => true]);

        foreach ($products as $product) {
            $product->previewImage()->getQuery()->delete();
            $product->imageUrls()->getQuery()->delete();
        }

        $previewImage = $this->getPreviewImage();

        /** @var Collection<Product> $hasImageProducts */
        $hasImageProducts = $products->random(4);
        foreach ($hasImageProducts as $key => $product) {
            if ($key === 1) {
                $product->previewImage()->associate($previewImage);
                $product->save();
            } else {
                ProductImageUrl::factory()->create([
                    'product_id' => $product,
                    'is_main' => true
                ]);
            }
        }

        $query['filter']['has_image'] = 1;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);

        $query['filter']['has_image'] = 0;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(6, $content['items']);
    }

    public function testSuccessfulFilterBySearch()
    {
        $products = Product::factory(10)->create(['setFull' => true]);

        $nameProducts = $products->random(2);
        /** @var Product $product */
        foreach ($nameProducts as $product) {
            $product->update(['name' => 'New Test ' . $product->name]);
        }

        $query['filter']['search'] = 'test';
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        /** @var Product $product */
        $product = $products->first();
        $query['filter']['search'] = $product->sku;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
    }

    public function testSuccessfulFilterByExcludeSku()
    {
        $products = Product::factory(10)->create(['setFull' => true]);

        $skuProducts = $products->random(2);
        /** @var Product $product */
        foreach ($skuProducts as $product) {
            $product->update(['sku' => 'Л' . $product->sku]);
        }

        $query['filter']['exclude_sku'] = 'Л';
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(8, $content['items']);
    }

    public function testSuccessfulFilterBySize()
    {
        $products = Product::factory(10)->create(['setFull' => true]);

        $productSizes = $products->random(2);
        $sizes = [];
        /** @var Product $product */
        foreach ($productSizes as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            $sizes[] = $offer->size;
        }

        $query['filter']['size'] = $sizes[0];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);

        $query['filter']['size'] = implode(',', $sizes);
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testFailurePagination()
    {
        $response = $this->get(self::METHOD . "?pagination[page]=1");
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . "?pagination[per_page]=3");
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);
    }

    public function testFailureSort()
    {
        $response = $this->get(self::METHOD . "?sort_by=price");
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . "?sort_order=desc");
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);
    }

    public function testFailureSortByUnknown()
    {
        Product::factory(5)->create(['setFull' => true]);

        $sort_by = 'unknown';
        $sort_order = SortOrderEnum::DESC->value;

        $response = $this->get(self::METHOD . "?sort_by={$sort_by}&sort_order={$sort_order}");
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureFilterByBrands()
    {
        $response = $this->get(self::METHOD . '?filter[brands]=1');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[brands]=brand1');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[brands][]=brand1');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[brands][]=0');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);
    }

    private function addProductOfferPrice(
        OfferPriceTypeEnum $type,
        int $amount,
        ?ProductOfferPrice $productOfferPrice = null
    ): ProductOfferPrice {
        $data = [
            'type' => $type,
            'is_active' => true,
            'price' => Money::RUB($amount * 100)
        ];

        if (!$productOfferPrice instanceof ProductOfferPrice) {
            /** @var Product $product */
            $product = Product::factory()->create(['setFull' => true]);
            /** @var ProductOffer $productOffer */
            $productOffer = $product->productOffers()->getQuery()->first();
            $data['product_offer_id'] = $productOffer;
            $productOffer->productOfferPrices()->getQuery()->update(['is_active' => false]);
        } else {
            $data['product_offer_id'] = $productOfferPrice->product_offer_id;
        }

        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create($data);

        return $productOfferPrice;
    }

    private function getPreviewImage(): PreviewImage
    {
        /** @var PreviewImage $previewImage */
        $previewImage = PreviewImage::factory()->create();
        Media::factory()->create([
            'model_type' => PreviewImage::class,
            'model_id' => $previewImage->getKey()
        ]);

        return $previewImage;
    }

    private function addLive(Product $product): void
    {
        LiveProduct::factory()->create(['product_id' => $product]);
    }
}
