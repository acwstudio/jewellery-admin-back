<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Wishlist;

use App\Modules\Users\Models\WishlistProduct;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\Feature\Http\Controllers\Users\UserTestCase;

class WishlistControllerDeleteTest extends UserTestCase
{
    private const METHOD = '/api/v1/user/wishlist/';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockMindboxApiClient();
    }

    public function testSuccessful(): void
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        /** @var WishlistProduct $wishlistProduct */
        $wishlistProduct = $this->createWishlistProducts(1, ['user_id' => $user])->first();

        $response = $this->delete(self::METHOD . $wishlistProduct->product_id);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testFailure(): void
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAuth(): void
    {
        /** @var WishlistProduct $wishlistProduct */
        $wishlistProduct = $this->createWishlistProducts()->first();

        $response = $this->delete(self::METHOD . $wishlistProduct->product_id);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function mockMindboxApiClient(): void
    {
        $this->mock(
            MindboxApiClientContract::class,
            function (MockInterface $mock) {
                $mock->allows('send');
                $mock->allows('websiteSetWithList');
            }
        );
    }
}
