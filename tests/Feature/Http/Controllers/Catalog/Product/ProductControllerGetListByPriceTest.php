<?php

declare(strict_types=1);

namespace Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Models\Product;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use Money\Money;
use Tests\TestCase;

class ProductControllerGetListByPriceTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product';

    public function testSuccessfulFilterByPrice()
    {
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20030);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 30040);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 40050);

        $query = [
            'filter' => [
                'price' => [
                    'min' => 1,
                    'max' => 30000
                ]
            ],
            'pagination' => [
                'page' => 1,
                'per_page' => 60
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulFilterByPriceOnlyMin()
    {
        $productOfferPriceOne = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 10020, $productOfferPriceOne);
        $this->addProductOfferPrice(OfferPriceTypeEnum::EMPLOYEE, 30020, $productOfferPriceOne);

        $productOfferPriceTwo = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 25020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 15020, $productOfferPriceTwo);
        $this->addProductOfferPrice(OfferPriceTypeEnum::EMPLOYEE, 30020, $productOfferPriceTwo);

        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 50050);

        $query = [
            'filter' => [
                'price' => [
                    'min' => 15000,
                    'max' => 16000
                ]
            ]
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
    }

    public function testSuccessfulFilterByPrices()
    {
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 25000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 30000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 35000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 40000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 45000);

        $query = [
            'filter' => [
                'prices' => '20000-25000,40000-45000,60000'
            ],
            'pagination' => [
                'page' => 1,
                'per_page' => 60
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
    }

    public function testSuccessfulFilterByPricesIgnore()
    {
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 25000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 30000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 35000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 40000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 45000);

        $query = [
            'filter' => [
                'price' => [
                    'min' => 20000, 'max' => 25000,
                ],
                'prices' => '20000-25000,40000-45000'
            ],
            'pagination' => [
                'page' => 1,
                'per_page' => 60
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulFilterAndSortByPrice()
    {
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20020);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 20030);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 30040);
        $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 40050);

        $query = [
            'sort_by' => ProductSortColumnEnum::PRICE->value,
            'sort_order' => SortOrderEnum::DESC->value,
            'filter' => [
                'price' => [
                    'min' => 1,
                    'max' => 30000
                ]
            ],
            'pagination' => [
                'page' => 1,
                'per_page' => 60
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulFilterByOfferPriceType()
    {
        $products = Product::factory(10)->create(['setFull' => true]);

        $productLivePrice = $products->random(2);

        /** @var Product $product */
        foreach ($productLivePrice as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $offer->getKey(),
                'type' => OfferPriceTypeEnum::LIVE,
                'is_active' => true
            ]);
        }

        $prices = [
            OfferPriceTypeEnum::LIVE->value,
            OfferPriceTypeEnum::REGULAR->value
        ];

        $query['filter']['offer_price_type'] = implode(',', $prices);
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(10, $content['items']);

        $query['filter']['offer_price_type'] = OfferPriceTypeEnum::LIVE->value;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulSortByPrice()
    {
        $productOfferPriceOne = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 2000, $productOfferPriceOne);
        $this->addProductOfferPrice(OfferPriceTypeEnum::EMPLOYEE, 4000, $productOfferPriceOne);
        $productIdOne = $productOfferPriceOne->productOffer->product->id;

        $productOfferPriceTwo = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 2000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 1000, $productOfferPriceTwo);
        $this->addProductOfferPrice(OfferPriceTypeEnum::EMPLOYEE, 3000, $productOfferPriceTwo);
        $productIdTwo = $productOfferPriceTwo->productOffer->product->id;

        $query = [
            'sort_by' => ProductSortColumnEnum::PRICE->value,
            'sort_order' => SortOrderEnum::DESC->value
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdOne, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdTwo, $lastItem['id']);

        $query['sort_order'] = SortOrderEnum::ASC->value;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdTwo, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdOne, $lastItem['id']);
    }

    public function testSuccessfulSortByDiscount()
    {
        $productOfferPriceOne = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 1900, $productOfferPriceOne);
        $productIdOne = $productOfferPriceOne->productOffer->product->id;

        $productOfferPriceTwo = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 100, $productOfferPriceTwo);
        $productIdTwo = $productOfferPriceTwo->productOffer->product->id;

        $productOfferPriceThree = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $productIdThree = $productOfferPriceThree->productOffer->product->id;

        $query = [
            'sort_by' => ProductSortColumnEnum::DISCOUNT->value,
            'sort_order' => SortOrderEnum::DESC->value
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdTwo, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdThree, $lastItem['id']);

        $query['sort_order'] = SortOrderEnum::ASC->value;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdOne, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdThree, $lastItem['id']);
    }

    public function testSuccessfulSortByDiscountIgnoreLive()
    {
        /** @var Product $productOne */
        $productOne = Product::factory()->create(['setFull' => true]);
        $productOfferOne = $productOne->productOffers->first();
        $this->createOrUpdateOfferPrice($productOfferOne, 2000, OfferPriceTypeEnum::REGULAR);
        $this->createOrUpdateOfferPrice($productOfferOne, 1000, OfferPriceTypeEnum::PROMO);

        /** @var Product $productTwo */
        $productTwo = Product::factory()->create(['setFull' => true]);
        $productOfferTwo = $productTwo->productOffers->first();
        $this->createOrUpdateOfferPrice($productOfferTwo, 2000, OfferPriceTypeEnum::REGULAR);
        $this->createOrUpdateOfferPrice($productOfferTwo, 1500, OfferPriceTypeEnum::PROMO);

        /** @var Product $productThree */
        $productThree = Product::factory()->create(['setFull' => true]);
        $productOfferThree = $productThree->productOffers->first();
        $this->createOrUpdateOfferPrice($productOfferThree, 2000, OfferPriceTypeEnum::REGULAR);
        $this->createOrUpdateOfferPrice($productOfferThree, 1200, OfferPriceTypeEnum::PROMO);
        $this->createOrUpdateOfferPrice($productOfferThree, 1500, OfferPriceTypeEnum::LIVE);

        $query = [
            'sort_by' => ProductSortColumnEnum::DISCOUNT->value,
            'sort_order' => SortOrderEnum::DESC->value
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $last = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $last);
        self::assertEquals($productThree->getKey(), $last['id']);

        $query['sort_order'] = SortOrderEnum::ASC->value;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $last = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $last);
        self::assertEquals($productThree->getKey(), $last['id']);
    }

    public function testSuccessfulSortByDiscountSale()
    {
        $productOfferPriceOne = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 1900, $productOfferPriceOne);
        $this->addProductOfferPrice(OfferPriceTypeEnum::SALE, 100, $productOfferPriceOne);
        $productIdOne = $productOfferPriceOne->productOffer->product->id;

        $productOfferPriceTwo = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $this->addProductOfferPrice(OfferPriceTypeEnum::PROMO, 100, $productOfferPriceTwo);
        $this->addProductOfferPrice(OfferPriceTypeEnum::SALE, 1900, $productOfferPriceTwo);
        $productIdTwo = $productOfferPriceTwo->productOffer->product->id;

        $productOfferPriceThree = $this->addProductOfferPrice(OfferPriceTypeEnum::REGULAR, 3000);
        $productIdThree = $productOfferPriceThree->productOffer->product->id;

        $query = [
            'sort_by' => ProductSortColumnEnum::DISCOUNT->value,
            'sort_order' => SortOrderEnum::DESC->value
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdOne, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdThree, $lastItem['id']);

        $query['sort_order'] = SortOrderEnum::ASC->value;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $firstItem = $content['items'][array_key_first($content['items'])];
        self::assertArrayHasKey('id', $firstItem);
        self::assertEquals($productIdTwo, $firstItem['id']);
        $lastItem = $content['items'][array_key_last($content['items'])];
        self::assertArrayHasKey('id', $lastItem);
        self::assertEquals($productIdThree, $lastItem['id']);
    }

    public function testFailureFilterByPrice()
    {
        $response = $this->get(self::METHOD . '?filter[price]=1000');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[price][]=1000&filter[price][]=2000');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[price][min]=1000&filter[price][]=2000');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[price][min]=more&filter[price][max]=2000');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);

        $response = $this->get(self::METHOD . '?filter[price][min]=5000&filter[price][max]=2000');
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

    private function createOrUpdateOfferPrice(
        ProductOffer $productOffer,
        int $amount,
        OfferPriceTypeEnum $type
    ): ProductOfferPrice {
        $productOfferPrice = $productOffer->productOfferPrices()
            ->getQuery()
            ->where('type', '=', $type)
            ->get()
            ->first();

        if ($productOfferPrice instanceof ProductOfferPrice) {
            $productOfferPrice->update([
                'type' => $type,
                'is_active' => true,
                'price' => Money::RUB($amount * 100)
            ]);

            return $productOfferPrice;
        }

        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer,
            'type' => $type,
            'is_active' => true,
            'price' => Money::RUB($amount * 100)
        ]);

        return $productOfferPrice;
    }
}
