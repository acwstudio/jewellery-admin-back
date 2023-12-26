<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Favorite;

use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class FavoriteControllerGetListTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/favorite';

    public function testSuccessful()
    {
        $this->createFavorites(5);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }
}
