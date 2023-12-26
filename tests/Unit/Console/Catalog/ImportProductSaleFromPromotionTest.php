<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Tests\TestCase;

class ImportProductSaleFromPromotionTest extends TestCase
{
    private const COMMAND = 'import:product_sale:promotion';

    public function testSuccessful()
    {
        $promotion = $this->createPromotion(5);
        $this->artisan(self::COMMAND, ['promotion_id' => $promotion->id]);

        $productOfferPrices = ProductOfferPrice::query()
            ->where('type', '=', OfferPriceTypeEnum::SALE)
            ->get();

        self::assertNotEmpty($productOfferPrices);
        self::assertCount(5, $productOfferPrices);
    }

    private function createPromotion(int $countProducts = 1): Promotion
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

        $products = Product::factory($countProducts)->create(['setFull' => true]);
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
