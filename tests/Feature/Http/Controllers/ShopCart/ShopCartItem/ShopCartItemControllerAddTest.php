<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ShopCart\ShopCartItem;

use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Modules\ShopCart\Models\ShopCart;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\ShopCart\ShopCartTestCase;

class ShopCartItemControllerAddTest extends ShopCartTestCase
{
    private const METHOD = '/api/v1/shop_cart/item';

    public function testSuccessful()
    {
        $productOffer = $this->createProductOffer();

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 1
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertEquals(1, $item['count']);
            self::assertArrayHasKey('selected', $item);
            self::assertFalse($item['selected']);
        }
    }

    public function testSuccessfulAddNew()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCart, $this->createProductOffer(), 2);

        $productOffer = $this->createProductOffer();

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 2
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertEquals(2, $item['count']);
            self::assertArrayHasKey('selected', $item);
            self::assertFalse($item['selected']);
        }
    }

    public function testSuccessfulSelected()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCart, $this->createProductOffer(), 2);

        $productOffer = $this->createProductOffer();

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 1,
                    'selected' => true
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('selected', $item);
            if ($item['product_offer_id'] === $productOffer->getKey()) {
                self::assertTrue($item['selected']);
            }
        }
    }

    public function testSuccessfulUpdateCountIncrement()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $productOffer = $this->createProductOffer();

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $shopCartItem = $this->addShopCartItem($shopCart, $productOffer, 2);

        $countNew = $shopCartItem->count + 1;
        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => $countNew
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertEquals(3, $item['count']);
        }
    }

    public function testSuccessfulUpdateCountDecrement()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $productOffer = $this->createProductOffer();

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $shopCartItem = $this->addShopCartItem($shopCart, $productOffer, 2);

        $countNew = $shopCartItem->count - 1;
        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => $countNew
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertEquals(1, $item['count']);
        }
    }

    public function testFailure()
    {
        $data = [
            'items' => [
                [
                    'product_id' => 100500,
                    'product_offer_id' => 100500,
                    'count' => 2
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureStockExceeded()
    {
        $productOffer = $this->createProductOffer(1);

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 2
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureStockExceededAdd()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $productOffer = $this->createProductOffer(2);

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCart, $productOffer);

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 3
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureStockExceededReservation()
    {
        $productOffer = $this->createProductOffer(2);

        ProductOfferReservation::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'count' => 2,
            'status' => OfferReservationStatusEnum::PENDING
        ]);

        $data = [
            'items' => [
                [
                    'product_id' => $productOffer->product->id,
                    'product_offer_id' => $productOffer->getKey(),
                    'count' => 1
                ]
            ]
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
