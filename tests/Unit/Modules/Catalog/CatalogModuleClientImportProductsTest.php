<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Packages\Events\Sync\ProductsImported;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CatalogModuleClientImportProductsTest extends TestCase
{
    private CatalogModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(CatalogModuleClientInterface::class);
        Event::fake([ProductsImported::class]);
    }

    public function testSuccessfulImportProduct()
    {
        Product::query()->delete();

        $message = json_decode(
            file_get_contents($this->getTestResources('test_Products_1C-Site.json')),
            true
        );

        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importProducts();

        $products = Product::query()->get();
        self::assertNotEmpty($products);

        /** @var Product $product */
        foreach ($products as $product) {
            self::assertTrue($product->productFeatures->isNotEmpty());
            self::assertTrue($product->videoUrls->isNotEmpty());
        }
    }
}
