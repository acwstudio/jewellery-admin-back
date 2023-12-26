<?php

declare(strict_types=1);

namespace Console\Promotions;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ImportPromotionsTest extends TestCase
{
    private const COMMAND = 'import:promotions';

    public function testSuccessfulSales()
    {
        Promotion::query()->delete();

        $message = json_decode(
            file_get_contents($this->getTestResources('Sales_Promotions_1C-Site.json')),
            true
        );

        $this->mockAMQPModuleClient($message);
        $this->artisan(self::COMMAND);

        $promotions = Promotion::query()->get();
        self::assertNotEmpty($promotions);

        /** @var Promotion $promotion */
        $promotion = $promotions->first();
        $benefits = $promotion->benefits;
        self::assertCount(1, $benefits);

        /** @var \App\Modules\Promotions\Models\PromotionBenefit $benefit */
        $benefit = $benefits->first();
        self::assertEquals(PromotionBenefitTypeEnum::SALE, $benefit->type);
        self::assertEquals(PromotionBenefitTypeFormEnum::SALE_PRICE, $benefit->type_form);

        $products = $benefit->products;
        self::assertNotEmpty($products);
        foreach ($products as $product) {
            self::assertInstanceOf(PromotionBenefitProduct::class, $product);
        }
    }

    public function testSuccessfulByProducts()
    {
        Promotion::query()->delete();

        $products = Product::factory(5)->create(['setFull' => true]);
        $message = $this->getMessage($products);

        $this->mockAMQPModuleClient($message);
        $this->artisan(self::COMMAND);

        $promotions = Promotion::query()->get();
        self::assertNotEmpty($promotions);

        /** @var Promotion $promotion */
        $promotion = $promotions->first();
        $benefits = $promotion->benefits;
        self::assertCount(1, $benefits);

        /** @var \App\Modules\Promotions\Models\PromotionBenefit $benefit */
        $benefit = $benefits->first();
        self::assertEquals(PromotionBenefitTypeEnum::SALE, $benefit->type);
        self::assertEquals(PromotionBenefitTypeFormEnum::SALE_PRICE, $benefit->type_form);

        $benefitProducts = $benefit->products;
        self::assertNotEmpty($benefitProducts);
        foreach ($benefitProducts as $product) {
            self::assertInstanceOf(PromotionBenefitProduct::class, $product);
        }

        $sale = Sale::query()->where('promotion_id', '=', $promotion->id)->get()->first();
        self::assertInstanceOf(Sale::class, $sale);
        self::assertNotEmpty($sale->products);

        /** @var Product $product */
        foreach ($products as $product) {
            $product->refresh();
            $offer = $product->productOffers->first();
            self::assertInstanceOf(ProductOffer::class, $offer);
            $salePrice = $offer->productOfferPrices
                ->where('type', '=', OfferPriceTypeEnum::SALE)->first();
            self::assertInstanceOf(ProductOfferPrice::class, $salePrice);
        }
        self::assertInstanceOf(Sale::class, $sale);
        self::assertNotEmpty($sale->products);
    }

    public function testSuccessfulPromocode()
    {
        Promotion::query()->delete();

        $message = json_decode(
            file_get_contents($this->getTestResources('Promocode_Promotions_1C-Site.json')),
            true
        );

        $this->mockAMQPModuleClient($message);
        $this->artisan(self::COMMAND);

        $promotions = Promotion::query()->get();
        self::assertNotEmpty($promotions);

        /** @var Promotion $promotion */
        $promotion = $promotions->first();
        $benefits = $promotion->benefits;
        self::assertTrue($benefits->isNotEmpty());

        foreach ($benefits as $benefit) {
            self::assertEquals(PromotionBenefitTypeEnum::PROMOCODE, $benefit->type);
            self::assertNotEmpty($benefit->promocode);
        }
    }

    private function getMessage(Collection $products): array
    {
        $message = json_decode(
            file_get_contents($this->getTestResources('Sales_Promotions_1C-Site.json')),
            true
        );

        /** @var \App\Modules\Catalog\Models\Product $product */
        foreach ($products as $product) {
            $message['sale'][0]['products'][] = [
                'vendorCode' => $product->sku,
                'UID' => $product->external_id,
                'size' => '',
                'price' => 2499
            ];
        }

        return $message;
    }
}
