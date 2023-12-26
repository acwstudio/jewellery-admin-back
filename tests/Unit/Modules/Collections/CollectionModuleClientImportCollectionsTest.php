<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CollectionModuleClientImportCollectionsTest extends TestCase
{
    private CollectionModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CollectionModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $collections = CollectionModel::query()->get();
        self::assertTrue($collections->isEmpty());

        $message = json_decode(
            file_get_contents($this->getTestResources('Collections_1C-Site.json')),
            true
        );

        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importCollections();

        $collections = CollectionModel::query()->get();
        self::assertTrue($collections->isNotEmpty());
    }

    public function testSuccessfulByProducts()
    {
        $collections = CollectionModel::query()->get();
        self::assertTrue($collections->isEmpty());

        $message = json_decode(
            file_get_contents($this->getTestResources('Collections_1C-Site.json')),
            true
        );

        $message = $this->addProducts($message);
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importCollections();

        $collections = CollectionModel::query()->get();
        self::assertTrue($collections->isNotEmpty());

        /** @var CollectionModel $collection */
        $collection = $collections->first();
        self::assertCount(5, $collection->products()->allRelatedIds());
    }

    public function testSuccessfulUpdate()
    {
        /** @var CollectionModel $collection */
        $collection = CollectionModel::factory()->create();

        $message = json_decode(
            file_get_contents($this->getTestResources('Collections_1C-Site.json')),
            true
        );

        $message = $this->addProducts($message);
        $message['name'] = $collection->name;
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importCollections();

        $collection->refresh();
        self::assertEquals($message['ID'], $collection->external_id);
        self::assertCount(5, $collection->products()->allRelatedIds());
    }

    public function testSuccessfulUpdateOnlyId()
    {
        Config::set('collections.import.collections.update.only_id', true);

        /** @var CollectionModel $collection */
        $collection = CollectionModel::factory()->create();

        $message = json_decode(
            file_get_contents($this->getTestResources('Collections_1C-Site.json')),
            true
        );

        $message['name'] = $collection->name;
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importCollections();

        $collection->refresh();
        self::assertEquals($message['ID'], $collection->external_id);
    }

    private function addProducts(array $message): array
    {
        $products = Product::factory(5)->create(['setFull' => true]);

        $message['products'] = $products->pluck('sku')->all();

        return $message;
    }
}
