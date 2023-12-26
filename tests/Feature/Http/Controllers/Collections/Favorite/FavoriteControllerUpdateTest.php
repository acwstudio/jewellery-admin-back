<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Favorite;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class FavoriteControllerUpdateTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/favorite/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(
            $this->getUser(RoleEnum::ADMIN)
        );
    }

    public function testSuccessful()
    {
        /** @var Favorite $favorite */
        $favorite = $this->createFavorites(1)->first();

        /** @var File $file */
        $file = $this->createFiles(1)->first();

        /** @var Collection $collection */
        $collection = $this->createCollections(1)->first();

        $response = $this->put(self::METHOD . $favorite->getKey(), [
            'slug' => fake()->slug(),
            'name' => fake()->text(50),
            'description' => fake()->text(50),
            'background_color' => fake()->hexColor(),
            'collection_id' => $collection->getKey(),
            'image_id' => $file->getKey(),
            'image_mob_id' => $file->getKey()
        ]);
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

    public function testFailure()
    {
        $response = $this->put(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
