<?php

declare(strict_types=1);

namespace Http\Controllers\Promotions\SaleProduct;

use App\Modules\Catalog\Models\Product as CatalogProduct;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use Carbon\Carbon;
use Tests\TestCase;

class SaleProductControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/promotions/sale_product';

    public function testSuccessful()
    {
        $this->createProducts(5);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulByPagination()
    {
        $this->createProducts(5);

        $query = [
            'pagination' => [
                'page' => 1,
                'per_page' => 3
            ]
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulFilterBySlug()
    {
        $this->createProducts();

        $sale1 = $this->createSale();
        $this->createProducts(2, $sale1);

        $sale2 = $this->createSale();
        $this->createProducts(3, $sale2);

        $saleNotActive = $this->createSale(['is_active' => false]);
        $this->createProducts(1, $saleNotActive);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(6, $content['items']);

        $query = ['sale_slug' => $sale1->slug];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);

        $query = ['sale_slug' => $sale2->slug];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);

        $query = ['sale_slug' => $sale1->slug . ',' . $sale2->slug];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulFilterByIsActive()
    {
        $saleStart = $this->createSale(
            ['is_active' => true],
            ['start_at' => Carbon::now()->addDays(2), 'finish_at' => Carbon::now()->addDays(3)]
        );
        $this->createProducts(1, $saleStart);

        $saleFinish = $this->createSale(
            ['is_active' => true],
            ['start_at' => Carbon::now()->subDays(3), 'finish_at' => Carbon::now()->subDays(2)]
        );
        $this->createProducts(2, $saleFinish);

        $saleActive = $this->createSale(
            ['is_active' => false],
            ['start_at' => Carbon::now()->subDays(3), 'finish_at' => Carbon::now()->addDays(2)]
        );
        $this->createProducts(3, $saleActive);

        $query = ['is_active' => true];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);

        $query['sale_id'] = $saleStart->id;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);

        $query = ['is_active' => false];
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(6, $content['items']);

        $query['sale_id'] = $saleStart->id;
        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
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

    private function createSale(
        array $promotionParam = [],
        array $promotionConditionParam = []
    ): Sale {
        $promotion = Promotion::factory()->create($promotionParam);

        if (empty($promotionConditionParam['promotion_id'])) {
            $promotionConditionParam['promotion_id'] = $promotion;
        }
        PromotionCondition::factory()->create($promotionConditionParam);

        /** @var Sale $sale */
        $sale = Sale::factory()->create(['promotion_id' => $promotion]);

        return $sale;
    }

    private function createProducts(int $count = 1, ?Sale $sale = null): void
    {
        if (null === $sale) {
            $sale = $this->createSale();
        }

        for ($i = 1; $i <= $count; $i++) {
            SaleProduct::factory()->create([
                'sale_id' => $sale,
                'product_id' => CatalogProduct::factory()->create(['setFull' => true])
            ]);
        }
    }
}
