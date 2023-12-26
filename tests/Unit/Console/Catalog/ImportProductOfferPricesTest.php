<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ImportProductOfferPricesTest extends TestCase
{
    private const COMMAND = 'import:product_offer_prices ';

    public function testSuccessfulByLive()
    {
        $products = Product::factory(1)->create(['setFull' => true]);

        /** @var Product $product */
        foreach ($products as $product) {
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertNotInstanceOf(ProductOfferPrice::class, $price);
        }

        $message = $this->getData($products, OfferPriceTypeEnum::LIVE);
        $this->mockAMQPModuleClient($message[0]);
        $this->artisan(self::COMMAND . OfferPriceTypeEnum::LIVE->value);

        /** @var Product $product */
        foreach ($products as $product) {
            $product->refresh();
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertInstanceOf(ProductOfferPrice::class, $price);
        }
    }

    public function testSuccessfulByRegular()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        /** @var Product $product */
        $product = $products->random();

        $price = $this->getPrice($product, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertNotEquals(1699988800, $price->price->getAmount());

        $message = $this->getData(collect([$product]), OfferPriceTypeEnum::REGULAR);
        $this->mockAMQPModuleClient($message);
        $this->artisan(self::COMMAND . OfferPriceTypeEnum::REGULAR->value);

        $product->refresh();
        $price = $this->getPrice($product, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(1699988800, $price->price->getAmount());
    }

    private function getData(Collection $products, OfferPriceTypeEnum $type): array
    {
        return match ($type) {
            OfferPriceTypeEnum::LIVE => $this->getDataPriceLive($products),
            OfferPriceTypeEnum::REGULAR => $this->getDataPriceRegular($products),
            default => []
        };
    }

    private function getDataPriceLive(Collection $products): array
    {
        $data = [];

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            $data[] = [
                'UID' => $product->external_id,
                'VendorCode' => $product->sku,
                'Date_time' => Carbon::now()->toRfc3339String(),
                'OnlinePrice' => 16999,
                'Size' => $offer->size,
            ];
        }

        return $data;
    }

    private function getDataPriceRegular(Collection $products): array
    {
        /** @var Product $product */
        $product = $products->first();
        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        return [
            'UID' => $product->external_id,
            'VendorCode' => $product->sku,
            'Date_time' => Carbon::now()->toRfc3339String(),
            'RegularPrice' => 16999888,
            'Size' => $offer->size,
        ];
    }

    private function getPrice(Product $product, OfferPriceTypeEnum $type): ?ProductOfferPrice
    {
        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        /** @var ProductOfferPrice|null $price */
        $price = $offer->productOfferPrices()
            ->getQuery()
            ->where('type', '=', $type)
            ->where('is_active', '=', true)
            ->get()
            ->first();

        return $price;
    }
}
