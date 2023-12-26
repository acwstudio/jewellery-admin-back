<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CatalogModuleClientImportProductOfferPriceLiveTest extends TestCase
{
    private CatalogModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CatalogModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $products = Product::factory(1)->create(['setFull' => true]);

        /** @var Product $product */
        foreach ($products as $product) {
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertNotInstanceOf(ProductOfferPrice::class, $price);
        }

        $message = $this->getDataByProducts($products);
        $this->mockAMQPModuleClient($message[0]);
        $this->moduleClient->importProductOfferPrices(OfferPriceTypeEnum::LIVE);

        /** @var Product $product */
        foreach ($products as $product) {
            $product->refresh();
            $price = $this->getPrice($product, OfferPriceTypeEnum::LIVE);
            self::assertInstanceOf(ProductOfferPrice::class, $price);
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
                'UID' => $product->external_id,
                'VendorCode' => $product->sku,
                'Date_time' => Carbon::now()->toRfc3339String(),
                'OnlinePrice' => 16999,
                'Size' => $offer->size,
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
