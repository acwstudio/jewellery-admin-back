<?php

declare(strict_types=1);

namespace Modules\Collections\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Services\CollectionProductListItemService;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CollectionProductListItemServiceTest extends TestCase
{
    private CollectionProductListItemService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CollectionProductListItemService::class);
    }

    public function testSuccessfulGetList()
    {
        $collections = Collection::factory(3)->create();
        /** @var Collection $collection */
        foreach ($collections as $collection) {
            $collection->products()->sync(Product::factory(5)->create());
        }

        $results = $this->service->getList(new Pagination(1, 100));

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(15, $results->items());
    }
}
