<?php

declare(strict_types=1);

namespace Http\Controllers\Promotions\Sale;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SaleControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/promotions/sale';

    public function testSuccessful()
    {
        $this->createSales(3, true);
        $this->createSales(2, false);

        $response = $this->get(self::METHOD);
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

    private function createSales(int $count = 1, bool $isActive = true): Collection
    {
        $sales = new Collection();
        for ($i = 1; $i <= $count; $i++) {
            $promotion = Promotion::factory()->create(['is_active' => $isActive]);
            PromotionCondition::factory()->create(['promotion_id' => $promotion]);
            $sale = Sale::factory()->create(['promotion_id' => $promotion]);
            $sales->add($sale);
        }
        return $sales;
    }
}
