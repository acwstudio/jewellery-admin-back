<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\CollectionProduct;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class CollectionProductControllerGetListTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/collection/{id}/product';

    public function testSuccessful()
    {
        /** @var Collection $collection */
        $collection = $this->createCollections(1)->first();
        $collection->products()->sync(Product::factory(3)->create(['setFull' => true]));

        $response = $this->get($this->getMethod($collection));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    private function getMethod(Collection $collection): string
    {
        return $this->setParamsInString(
            ['id' => $collection->getKey()],
            self::METHOD
        );
    }
}
