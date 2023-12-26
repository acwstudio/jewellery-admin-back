<?php

declare(strict_types=1);

namespace Modules\Collections;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use App\Packages\DataObjects\Collections\CollectionProduct\CollectionProductListItemListData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Tests\TestCase;

class CollectionModuleClientTest extends TestCase
{
    private CollectionModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CollectionModuleClientInterface::class);
    }

    public function testSuccessfulGetCollectionProductListItems()
    {
        $this->createCollections(3, 5);
        $result = $this->moduleClient->getCollectionProductListItems();

        self::assertInstanceOf(CollectionProductListItemListData::class, $result);
        self::assertCount(15, $result->items);
    }

    private function createCollections(int $count = 1, int $productCount = 3): void
    {
        $collections = Collection::factory($count)->create();
        /** @var Collection $collection */
        foreach ($collections as $collection) {
            $collection->products()->sync(Product::factory($productCount)->create());
        }
    }
}
