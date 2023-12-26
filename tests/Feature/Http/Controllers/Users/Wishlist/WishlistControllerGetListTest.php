<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Wishlist;

use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Users\UserTestCase;

class WishlistControllerGetListTest extends UserTestCase
{
    private const METHOD = '/api/v1/user/wishlist';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        $this->createWishlistProducts(5, ['user_id' => $user]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulPagination()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        $this->createWishlistProducts(5, ['user_id' => $user]);

        $query = [
            'pagination' => [
                'page' => 1,
                'per_page' => 3
            ]
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulEmpty()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
