<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Stone;

use App\Modules\Collections\Models\Stone;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class StoneControllerGetListTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/stone';

    public function testSuccessful()
    {
        Stone::factory(5)->create();

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulFilterName()
    {
        Stone::factory(1)->create(['name' => 'Камень 1']);
        Stone::factory(1)->create(['name' => 'Мини камень 1']);
        Stone::factory(1)->create(['name' => 'Группа из каменных']);
        Stone::factory(2)->create();

        $response = $this->get(self::METHOD . "?filter[name]=камен");
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
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
