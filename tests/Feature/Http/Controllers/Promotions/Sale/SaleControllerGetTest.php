<?php

declare(strict_types=1);

namespace Http\Controllers\Promotions\Sale;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use Tests\TestCase;

class SaleControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/promotions/sale/';

    public function testSuccessful()
    {
        $sale = $this->createSale();

        $response = $this->get(self::METHOD . $sale->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('title', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('started_at', $content);
        self::assertArrayHasKey('expired_at', $content);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . fake()->slug);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createSale(): Sale
    {
        $promotion = Promotion::factory()->create();
        PromotionCondition::factory()->create(['promotion_id' => $promotion]);
        /** @var Sale $sale */
        $sale = Sale::factory()->create(['promotion_id' => $promotion]);

        return $sale;
    }
}
