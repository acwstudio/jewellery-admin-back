<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductLiveDataNormalizer;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ImportProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLivePriceData;
use Tests\TestCase;

class ProductLiveDataNormalizerTest extends TestCase
{
    private DataNormalizerInterface $dataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataNormalizer = new ProductLiveDataNormalizer();
    }

    public function testSuccessful()
    {
        $data = $this->dataNormalizer->normalize($this->getData());
        self::assertInstanceOf(ImportProductLiveData::class, $data);
        self::assertFalse($data->products->isEmpty());
        foreach ($data->products as $product) {
            self::assertInstanceOf(ProductLiveData::class, $product);
            self::assertFalse($product->prices->isEmpty());
            foreach ($product->prices as $price) {
                self::assertInstanceOf(ProductLivePriceData::class, $price);
            }
        }
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('OnlineProducts_1C-Site.json')),
            true
        );
    }
}
