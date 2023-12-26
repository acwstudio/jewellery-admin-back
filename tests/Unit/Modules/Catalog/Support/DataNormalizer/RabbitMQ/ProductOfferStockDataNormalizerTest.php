<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferStockDataNormalizer;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\ImportProductOfferStockData;
use Tests\TestCase;

class ProductOfferStockDataNormalizerTest extends TestCase
{
    private DataNormalizerInterface $dataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataNormalizer = new ProductOfferStockDataNormalizer();
    }

    public function testSuccessful()
    {
        $dataNormalize = $this->dataNormalizer->normalize($this->getData());
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
    }

    public function testSuccessfulEmptySize()
    {
        $dataNormalize = $this->dataNormalizer->normalize($this->getData(['Size' => '']));
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertNull($dataNormalize->size);

        $dataNormalize = $this->dataNormalizer->normalize($this->getData(['Size' => null]));
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertNull($dataNormalize->size);

        $data = $this->getData();
        unset($data['Size']);
        $dataNormalize = $this->dataNormalizer->normalize($data);
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertNull($dataNormalize->size);
    }

    public function testSuccessfulEmptyStockCount()
    {
        $dataNormalize = $this->dataNormalizer->normalize($this->getData(['StockCount' => '']));
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertEquals(0, $dataNormalize->count);

        $dataNormalize = $this->dataNormalizer->normalize($this->getData(['StockCount' => null]));
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertEquals(0, $dataNormalize->count);

        $data = $this->getData();
        unset($data['StockCount']);
        $dataNormalize = $this->dataNormalizer->normalize($data);
        self::assertInstanceOf(ImportProductOfferStockData::class, $dataNormalize);
        self::assertEquals(0, $dataNormalize->count);
    }

    private function getData(array $params = []): array
    {
        $data = json_decode(
            file_get_contents($this->getTestResources('Stocks_1C-Site.json')),
            true
        );

        return array_merge($data, $params);
    }
}
