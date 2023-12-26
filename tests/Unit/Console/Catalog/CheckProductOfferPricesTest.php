<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Money\Money;
use Tests\TestCase;

class CheckProductOfferPricesTest extends TestCase
{
    private const COMMAND = 'check:product_offer_prices';

    public function testSuccessful()
    {
        $products = $this->createProducts(5);

        $this->createSale(
            ['is_active' => false],
            ['start_at' => Carbon::now()->subDays(3), 'finish_at' => Carbon::now()->subDays(2)],
            $products
        );

        $this->createLiveProducts($products, false);

        $saleProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::SALE, true);
        self::assertNotEmpty($saleProductOfferPrices);
        self::assertCount(5, $saleProductOfferPrices);
        $liveProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::LIVE, true);
        self::assertNotEmpty($liveProductOfferPrices);
        self::assertCount(5, $liveProductOfferPrices);

        $this->artisan(self::COMMAND);

        $saleActiveProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::SALE, true);
        self::assertEmpty($saleActiveProductOfferPrices);
        $saleNotActiveProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::SALE, false);
        self::assertNotEmpty($saleNotActiveProductOfferPrices);
        self::assertCount(5, $saleNotActiveProductOfferPrices);
        $liveActiveProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::LIVE, true);
        self::assertEmpty($liveActiveProductOfferPrices);
        $liveNotActiveProductOfferPrices = $this->getProductOfferPrices(OfferPriceTypeEnum::LIVE, false);
        self::assertNotEmpty($liveNotActiveProductOfferPrices);
        self::assertCount(5, $liveNotActiveProductOfferPrices);
    }

    private function createProducts(int $count = 1): Collection
    {
        $products = Product::factory($count)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $product) {
            $productOffer = $product->productOffers->first();
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $productOffer,
                'price' => Money::RUB(100 * 100),
                'type' => OfferPriceTypeEnum::LIVE,
            ]);
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $productOffer,
                'price' => Money::RUB(100 * 100),
                'type' => OfferPriceTypeEnum::SALE,
            ]);
            $product->refresh();
        }

        return $products;
    }

    private function createSale(
        array $promotionParam = [],
        array $promotionConditionParam = [],
        Collection $products = new Collection(),
    ): Sale {
        $promotion = Promotion::factory()->create($promotionParam);

        if (empty($promotionConditionParam['promotion_id'])) {
            $promotionConditionParam['promotion_id'] = $promotion;
        }
        PromotionCondition::factory()->create($promotionConditionParam);

        /** @var Sale $sale */
        $sale = Sale::factory()->create(['promotion_id' => $promotion]);

        if ($products->isEmpty()) {
            return $sale;
        }

        /** @var Product $product */
        foreach ($products as $product) {
            SaleProduct::factory()->create([
                'sale_id' => $sale,
                'product_id' => $product->id
            ]);
        }

        return $sale->refresh();
    }

    private function createLiveProducts(Collection $products, bool $isActive): void
    {
        if ($isActive) {
            $started_at = Carbon::now()->subDays(3);
            $expired_at = Carbon::now()->addDays(2);
        } else {
            $started_at = Carbon::now()->subDays(3);
            $expired_at = Carbon::now()->subDays(2);
        }

        foreach ($products as $key => $product) {
            LiveProduct::factory()->create([
                'product_id' => $product,
                'started_at' => $started_at,
                'expired_at' => $expired_at,
            ]);
        }
    }

    private function getProductOfferPrices(OfferPriceTypeEnum $type, bool $isActive): Collection
    {
        return ProductOfferPrice::query()
            ->where('is_active', '=', $isActive)
            ->where('type', '=', $type)
            ->get();
    }
}
