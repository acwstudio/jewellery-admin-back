<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\Monolith;

use App\Modules\Catalog\Support\DataNormalizer\Monolith\ProductFilterDataNormalizer;
use App\Packages\DataObjects\Catalog\Product\MonolithProductFilterData;
use Tests\TestCase;

class ProductFilterDataNormalizerTest extends TestCase
{
    public function testSuccessful()
    {
        $productFilterDataNormalizer = new ProductFilterDataNormalizer();

        $data = $productFilterDataNormalizer->normalize($this->getData());

        $this->assertInstanceOf(MonolithProductFilterData::class, $data);
    }

    private function getData(): array
    {
        return json_decode(
            file_get_contents($this->getTestResources('monolith_product_filter.json')),
            true
        );
    }
}
