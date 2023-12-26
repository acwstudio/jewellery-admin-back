<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections\Services;

use App\Modules\Collections\Models\Stone;
use App\Modules\Collections\Services\StoneService;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class StoneServiceTest extends TestCase
{
    private StoneService $stoneService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stoneService = app(StoneService::class);
    }

    public function testSuccessfulGet()
    {
        $stone = Stone::factory()->create();

        $results = $this->stoneService->getStone($stone->getKey());

        self::assertInstanceOf(Stone::class, $results);
    }

    public function testSuccessfulGetList()
    {
        Stone::factory(5)->create();
        $pagination = new Pagination(1, 10);

        $results = $this->stoneService->getStones($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertEquals(5, $results->total());
    }

    public function testSuccessfulCreate()
    {
        $results = $this->stoneService->createStone('Камень 1');

        self::assertInstanceOf(Stone::class, $results);
    }

    public function testSuccessfulDelete()
    {
        /** @var Stone $stone */
        $stone = Stone::factory()->create();

        $this->stoneService->deleteStone($stone->getKey());

        self::assertModelMissing($stone);
    }
}
