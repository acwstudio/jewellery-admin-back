<?php

declare(strict_types=1);

namespace Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Packages\Events\Sync\ProductOfferStocksImported;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CatalogModuleClientImportProductOfferStockTest extends TestCase
{
    private CatalogModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CatalogModuleClientInterface::class);
        Event::fake([ProductOfferStocksImported::class]);
    }

    public function testSuccessful()
    {
        $products = Product::factory(1)->create(['setFull' => true]);

        /** @var Product $product */
        foreach ($products as $product) {
            $stock = $this->getStock($product);
            self::assertInstanceOf(ProductOfferStock::class, $stock);
            self::assertEquals(5, $stock->count);
        }

        $message = $this->getDataByProducts($products);
        $this->mockAMQPModuleClient($message[0]);
        $this->moduleClient->importProductOfferStocks();

        /** @var Product $product */
        foreach ($products as $product) {
            $product->refresh();
            $stock = $this->getStock($product);
            self::assertInstanceOf(ProductOfferStock::class, $stock);
            self::assertEquals(100, $stock->count);
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
                'Size' => $offer->size,
                'Stock' => 'Офис',
                'StockCount' => 100,
            ];
        }

        return $data;
    }

    private function getStock(Product $product): ?ProductOfferStock
    {
        /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
        $offer = $product->productOffers->first();
        /** @var ProductOfferStock|null $model */
        $model = $offer->productOfferStocks()
            ->getQuery()
            ->where('is_current', '=', true)
            ->get()
            ->first();

        return $model;
    }
}
