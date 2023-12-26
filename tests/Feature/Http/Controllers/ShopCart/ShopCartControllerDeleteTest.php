<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ShopCart;

use App\Modules\ShopCart\Models\ShopCart;
use Laravel\Sanctum\Sanctum;

class ShopCartControllerDeleteTest extends ShopCartTestCase
{
    private const METHOD = '/api/v1/shop_cart';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);

        $this->addShopCartItem($shopCart, $this->createProductOffer());
        $this->addShopCartItem($shopCart, $this->createProductOffer(), 2);

        self::assertTrue($shopCart->items()->getQuery()->exists());

        $response = $this->delete(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertFalse($shopCart->refresh()->items()->getQuery()->exists());
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->delete(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }
}
