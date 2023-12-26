<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use Generator;

class ProductFilterImportService
{
    public function __construct(
        protected DataProviderInterface $productFilterDataProvider,
        protected DataNormalizerInterface $productFilterDataNormalizer
    ) {
    }

    public function import(): Generator
    {
        foreach ($this->productFilterDataProvider->getRawData() as $data) {
            yield $this->productFilterDataNormalizer->normalize($data);
        }
    }
}
