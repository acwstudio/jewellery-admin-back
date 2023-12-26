<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferPriceLiveDataNormalizer;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ImportProductOfferPriceLiveData;
use Tests\TestCase;

class ProductOfferPriceLiveDataNormalizerTest extends TestCase
{
    private DataNormalizerInterface $dataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataNormalizer = new ProductOfferPriceLiveDataNormalizer();
    }

    public function testSuccessful()
    {
        $data = $this->dataNormalizer->normalize($this->getData());
        $this->assertInstanceOf(ImportProductOfferPriceLiveData::class, $data);
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('OnlinePrices_1C-Site.json')),
            true
        );
    }
}
