<?php

declare(strict_types=1);

namespace Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductDataNormalizer;
use App\Packages\DataObjects\Catalog\Product\ImportProductData;
use Tests\TestCase;

class ProductDataNormalizerTest extends TestCase
{
    private DataNormalizerInterface $dataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataNormalizer = new ProductDataNormalizer();
    }

    public function testSuccessful()
    {
        $data = $this->dataNormalizer->normalize($this->getData());
        self::assertInstanceOf(ImportProductData::class, $data);
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('test_Products_1C-Site.json')),
            true
        );
    }
}
