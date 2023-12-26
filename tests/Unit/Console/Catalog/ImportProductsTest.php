<?php

declare(strict_types=1);

namespace Console\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Packages\Events\Sync\ProductsImported;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ImportProductsTest extends TestCase
{
    private const COMMAND = 'import:products';

    protected function setUp(): void
    {
        parent::setUp();
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
        $this->artisan(self::COMMAND);

        $products = Product::query()->get();
        self::assertNotEmpty($products);
        self::assertFalse($products->isEmpty());

        /** @var Product $product */
        foreach ($products as $product) {
            self::assertFalse($product->productOffers->isEmpty());
        }
    }
}
