<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CatalogModuleClientImportProductOfferPriceRegularTest extends TestCase
{
    private CatalogModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CatalogModuleClientInterface::class);
    }

    public function testSuccessful()
    {
        $products = Product::factory(3)->create(['setFull' => true]);

        /** @var Product $product */
        $product = $products->random();

        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        $price = $this->getPrice($offer, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertNotEquals(1699988800, $price->price->getAmount());

        $message = $this->getDataByProducts(collect([$product]));
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importProductOfferPrices(OfferPriceTypeEnum::REGULAR);

        $product->refresh();
        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        $price = $this->getPrice($offer, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(1699988800, $price->price->getAmount());
    }

    public function testSuccessfulByNullSize()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        /** @var Product $product */
        $product = $products->random();

        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();

        $price = $this->getPrice($offer, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertNotEquals(1699988800, $price->price->getAmount());

        $message = $this->getDataByProducts(collect([$product]), ['size' => '']);
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importProductOfferPrices(OfferPriceTypeEnum::REGULAR);

        $product->refresh();
        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();

        $price = $this->getPrice($offer, OfferPriceTypeEnum::REGULAR);
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(1699988800, $price->price->getAmount());
    }

    private function getDataByProducts(Collection $products, array $dataNew = []): array
    {
        /** @var Product $product */
        $product = $products->first();

        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        return [
            'UID' => $product->external_id,
            'VendorCode' => $dataNew['sku'] ?? $product->sku,
            'Date_time' => $dataNew['date_time'] ?? Carbon::now()->toRfc3339String(),
            'RegularPrice' => $dataNew['price'] ?? 16999888,
            'Size' => $dataNew['size'] ?? $offer->size,
        ];
    }

    private function getPrice(ProductOffer $productOffer, OfferPriceTypeEnum $type): ?ProductOfferPrice
    {
        /** @var ProductOfferPrice|null $price */
        $price = $productOffer->productOfferPrices()
            ->getQuery()
            ->where('type', '=', $type)
            ->where('is_active', '=', true)
            ->get()
            ->first();

        return $price;
    }
}
