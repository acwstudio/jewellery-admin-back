<?php

declare(strict_types=1);

namespace Modules\Promotions;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Packages\DataObjects\Promotions\Promocode\Price\Filter\FilterPromocodePriceData;
use App\Packages\DataObjects\Promotions\Promocode\Price\GetPromocodePriceListData;
use App\Packages\DataObjects\Promotions\Promocode\Price\PromocodePriceData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CatalogModuleClientGetPromocodePricesTest extends TestCase
{
    private PromotionsModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(PromotionsModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $promocodePrice = $this->getPromocodePrice();

        $result = $this->moduleClient->getPromocodePrices(
            new GetPromocodePriceListData(
                filter: new FilterPromocodePriceData(
                    shop_cart_token: $promocodePrice->shop_cart_token,
                    product_offer_id: $promocodePrice->product_offer_id
                )
            )
        );

        self::assertInstanceOf(Collection::class, $result);
        self::assertNotEmpty($result);
        self::assertInstanceOf(PromocodePriceData::class, $result->first());
    }

    private function createPromotion(): Promotion
    {
        /** @var Promotion $promotion */
        $promotion = Promotion::factory()->create();

        PromotionCondition::factory()->create(['promotion_id' => $promotion]);
        /** @var PromotionBenefit $promotionBenefit */
        $promotionBenefit = PromotionBenefit::factory()->create([
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::PROMOCODE
        ]);

        PromocodePrice::factory(5)->create([
            'promotion_benefit_id' => $promotionBenefit
        ]);

        return $promotion->refresh();
    }

    private function getPromocodePrice(): PromocodePrice
    {
        $promotion = $this->createPromotion();
        /** @var PromotionBenefit $benefit */
        $benefit = $promotion->benefits->first();

        /** @var PromocodePrice $promocodePrice */
        $promocodePrice = PromocodePrice::query()
            ->where('promotion_benefit_id', '=', $benefit->id)
            ->first();

        return $promocodePrice;
    }
}
