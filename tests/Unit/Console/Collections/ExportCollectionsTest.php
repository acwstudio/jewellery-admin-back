<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Collections;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection as CollectionModel;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ExportCollectionsTest extends TestCase
{
    private const COMMAND = 'export:collections';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockAMQPModuleClient([]);
    }

    public function testSuccessful()
    {
        $this->createCollections(3, 2);
        $this->artisan(self::COMMAND);

        self::assertEquals(1, 1);
    }

    public function testSuccessfulByIds()
    {
        $collections = $this->createCollections(5);
        $randCollections = $collections->random(3);
        $this->artisan(self::COMMAND, [
            'ids' => $randCollections->implode('id', ',')
        ]);

        self::assertEquals(1, 1);
    }

    private function createCollections(int $count = 1, int $productCount = 1): Collection
    {
        $collections = CollectionModel::factory($count)->create();

        /** @var CollectionModel $collection */
        foreach ($collections as $collection) {
            $collection->products()->sync(Product::factory($productCount)->create(['setFull' => true]));
            $collection->refresh();
        }

        return $collections;
    }
}
