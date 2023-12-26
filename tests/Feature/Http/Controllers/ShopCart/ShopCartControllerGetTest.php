<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ShopCart;

use App\Modules\ShopCart\Models\ShopCart;
use Laravel\Sanctum\Sanctum;

class ShopCartControllerGetTest extends ShopCartTestCase
{
    private const METHOD = '/api/v1/shop_cart';

    public function testSuccessful()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
    }

    public function testSuccessfulNewByUser()
    {
        $user = $this->getUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
    }

    public function testSuccessfulByToken()
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create();

        $this->addShopCartItem($shopCart, $this->createProductOffer());
        $this->addShopCartItem($shopCart, $this->createProductOffer(), 2);

        $response = $this->withHeader('shop-cart-token', $shopCart->token)->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_id', $item);
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('sku', $item);
            self::assertArrayHasKey('name', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertArrayHasKey('preview_image', $item);
            self::assertArrayHasKey('prices', $item);
        }
    }

    public function testSuccessfulByUser()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);

        $this->addShopCartItem($shopCart, $this->createProductOffer());
        $this->addShopCartItem($shopCart, $this->createProductOffer(), 2);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_id', $item);
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('sku', $item);
            self::assertArrayHasKey('name', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertArrayHasKey('preview_image', $item);
            self::assertArrayHasKey('prices', $item);
        }
    }

    public function testSuccessfulByUserAndToken()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var ShopCart $shopCartByUser */
        $shopCartByUser = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCartByUser, $this->createProductOffer());

        /** @var ShopCart $shopCartByToken */
        $shopCartByToken = ShopCart::factory()->create();
        $this->addShopCartItem($shopCartByToken, $this->createProductOffer());
        $this->addShopCartItem($shopCartByToken, $this->createProductOffer(), 2);

        $response = $this->withHeader('shop-cart-token', $shopCartByToken->token)
            ->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
        self::assertModelMissing($shopCartByUser);

        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_id', $item);
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('sku', $item);
            self::assertArrayHasKey('name', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertArrayHasKey('preview_image', $item);
            self::assertArrayHasKey('prices', $item);
        }
    }

    public function testSuccessfulByUserAndTokenMoveItems()
    {
        $user = $this->getUser();
        $userToken = $user->createToken('test')->plainTextToken;

        $itemOne = $this->createProductOffer();
        $itemTwo = $this->createProductOffer();

        /** @var ShopCart $shopCartByUser */
        $shopCartByUser = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCartByUser, $this->createProductOffer());
        $this->addShopCartItem($shopCartByUser, $itemOne);
        $this->addShopCartItem($shopCartByUser, $itemTwo);

        /** @var ShopCart $shopCartByToken */
        $shopCartByToken = ShopCart::factory()->create();
        $this->addShopCartItem($shopCartByToken, $this->createProductOffer());
        $this->addShopCartItem($shopCartByToken, $itemOne, 2);
        $this->addShopCartItem($shopCartByToken, $itemTwo, 3);

        $response = $this->withToken($userToken)->withHeader('shop-cart-token', $shopCartByToken->token)
            ->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
        self::assertModelMissing($shopCartByUser);

        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('product_id', $item);
            self::assertArrayHasKey('product_offer_id', $item);
            self::assertArrayHasKey('count', $item);
            self::assertArrayHasKey('sku', $item);
            self::assertArrayHasKey('name', $item);
            self::assertArrayHasKey('selected', $item);
            self::assertArrayHasKey('preview_image', $item);
            self::assertArrayHasKey('prices', $item);
        }
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testSuccessfulByTokenIncludeUserId()
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create();

        $response = $this->withHeader('shop-cart-token', $shopCart->token)->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('token', $content);
        self::assertArrayHasKey('items', $content);
        self::assertEquals($shopCart->token, $content['token']);

        $shopCart->update(['user_id' => $this->getUser()->user_id]);

        $response = $this->withHeader('shop-cart-token', $shopCart->token)->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('token', $content);
        self::assertArrayHasKey('items', $content);
        self::assertNotEquals($shopCart->token, $content['token']);
    }
}
