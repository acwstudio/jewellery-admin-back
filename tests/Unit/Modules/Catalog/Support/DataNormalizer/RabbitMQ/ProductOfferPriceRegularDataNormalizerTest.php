<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferPriceRegularDataNormalizer;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\Import\ImportProductOfferPriceRegularData;
use Tests\TestCase;

class ProductOfferPriceRegularDataNormalizerTest extends TestCase
{
    private DataNormalizerInterface $dataNormalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataNormalizer = new ProductOfferPriceRegularDataNormalizer();
    }

    public function testSuccessful()
    {
        $data = $this->dataNormalizer->normalize($this->getData());
        $this->assertInstanceOf(ImportProductOfferPriceRegularData::class, $data);
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('RegularPrices_1C-Site.json')),
            true
        );
    }
}
