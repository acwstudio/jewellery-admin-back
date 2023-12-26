<?php

declare(strict_types=1);

namespace Modules\Promotions;

use App\Modules\Catalog\Models\Product;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Packages\DataObjects\Promotions\Promotion\PromotionData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Tests\TestCase;

class CatalogModuleClientGetPromotionTest extends TestCase
{
    private PromotionsModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(PromotionsModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $promotion = $this->createPromotion();

        $result = $this->moduleClient->getPromotion($promotion->id);
        self::assertInstanceOf(PromotionData::class, $result);
    }

    private function createPromotion(): Promotion
    {
        /** @var Promotion $promotion */
        $promotion = Promotion::factory()->create();

        PromotionCondition::factory()->create(['promotion_id' => $promotion]);
        /** @var PromotionBenefit $promotionBenefit */
        $promotionBenefit = PromotionBenefit::factory()->create([
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::SALE,
            'type_form' => PromotionBenefitTypeFormEnum::SALE_PRICE
        ]);

        $products = Product::factory(5)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $product) {
            PromotionBenefitProduct::factory()->create([
                'promotion_benefit_id' => $promotionBenefit,
                'sku' => $product->sku,
                'external_id' => $product->external_id,
            ]);
        }

        return $promotion->refresh();
    }
}
