<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Favorite;

use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;
use App\Modules\Collections\Models\Favorite;

class FavoriteControllerGetTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/favorite/';

    public function testSuccessful()
    {
        /** @var Favorite $favorite */
        $favorite = $this->createFavorites(1)->first();

        $response = $this->get(self::METHOD . $favorite->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('background_color', $content);
        self::assertArrayHasKey('font_color', $content);
        self::assertArrayHasKey('collection_id', $content);
        self::assertArrayHasKey('collection_slug', $content);
        self::assertArrayHasKey('image', $content);
        self::assertArrayHasKey('image_mob', $content);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD . fake()->slug());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }
}
