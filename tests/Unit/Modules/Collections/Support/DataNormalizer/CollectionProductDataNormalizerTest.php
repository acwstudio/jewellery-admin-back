<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections\Support\DataNormalizer;

use App\Modules\Collections\Support\DataNormalizer\Monolith\CollectionProductDataNormalizer;
use App\Packages\DataObjects\Collections\CollectionProduct\ImportCollectionProductData;
use Tests\TestCase;

class CollectionProductDataNormalizerTest extends TestCase
{
    public function testSuccessful()
    {
        $dataNormalizer = new CollectionProductDataNormalizer();

        $data = $dataNormalizer->normalize($this->getData());

        $this->assertInstanceOf(ImportCollectionProductData::class, $data);
    }

    private function getData(): array
    {
        return [
            'collection_id' => 1,
            'product_ids' => [1, 2, 3],
            'category_ids' => [8, 10, 15]
        ];
    }
}
