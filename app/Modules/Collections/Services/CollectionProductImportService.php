<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Collections\Support\DataProvider\DataProviderInterface;
use Generator;

class CollectionProductImportService
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
