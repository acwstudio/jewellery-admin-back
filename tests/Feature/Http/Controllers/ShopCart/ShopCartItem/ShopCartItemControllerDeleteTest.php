<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ShopCart\ShopCartItem;

use App\Modules\ShopCart\Models\ShopCart;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\ShopCart\ShopCartTestCase;

class ShopCartItemControllerDeleteTest extends ShopCartTestCase
{
    private const METHOD = '/api/v1/shop_cart/item';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $productOffer = $this->createProductOffer();

        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);
        $this->addShopCartItem($shopCart, $productOffer, 2);

        $response = $this->delete(self::METHOD, ['product_offer_ids' => [$productOffer->getKey()]]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertNull($content);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD, ['product_offer_ids' => [100500]]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
