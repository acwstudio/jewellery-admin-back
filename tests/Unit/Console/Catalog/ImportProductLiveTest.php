<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Live\Models\LiveProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ImportProductLiveTest extends TestCase
{
    private const COMMAND = 'import:product_live';

    public function testSuccessful()
    {
        $products = Product::factory(3)->create(['setFull' => true, 'is_active' => false]);

        /** @var Product $product */
        foreach ($products as $product) {
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertNotInstanceOf(ProductOfferPrice::class, $price);
            self::assertFalse($product->is_active);
        }

        $message = $this->getDataByProducts($products);
        $this->mockAMQPModuleClient($message);
        $this->artisan(self::COMMAND);

        /** @var Product $product */
        foreach ($products as $product) {
            $product->refresh();
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertInstanceOf(ProductOfferPrice::class, $price);
            self::assertTrue($product->is_active);
        }

        $this->assertProductsOnLive($products);
    }

    private function assertProductsOnLive(Collection $products): void
    {
        $liveProducts = LiveProduct::query()->where('on_live', '=', true)->get();

        /** @var Product $product */
        foreach ($products as $product) {
            $liveProduct = $liveProducts->where('product_id', '=', $product->getKey())->first();
            self::assertInstanceOf(LiveProduct::class, $liveProduct);
        }
    }

    private function getDataByProducts(Collection $products): array
    {
        $data = [];

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            $data[] = [
                'Art' => $product->sku,
                'Slot' => 0,
                'Prices' => [
                    [
                        'Size' => $offer->size,
                        'OnlinePrice' => 16999
                    ]
                ],
                'Date_time' => '2023-09-28T19:11:14+03:00'
            ];
        }

        return $data;
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
