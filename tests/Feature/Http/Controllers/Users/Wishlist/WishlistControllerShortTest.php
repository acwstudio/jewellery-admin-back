<?php

declare(strict_types=1);

namespace Http\Controllers\Users\Wishlist;

use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Users\UserTestCase;

class WishlistControllerShortTest extends UserTestCase
{
    private const METHOD = '/api/v1/user/wishlist/short';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        $this->createWishlistProducts(5, ['user_id' => $user]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertEquals(5, $content['count']);
    }

    public function testSuccessfulEmpty()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertEquals(0, $content['count']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
