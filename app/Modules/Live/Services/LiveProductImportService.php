<?php

declare(strict_types=1);

namespace App\Modules\Live\Services;

use App\Modules\Live\Support\DataNormalizer\DataNormalizerInterface;
use Generator;

class LiveProductImportService
{
    public function __construct(
        protected DataNormalizerInterface $liveProductDataNormalizer
    ) {
    }

    public function import(iterable $rawData): Generator
    {
        foreach ($rawData as $data) {
            yield $this->liveProductDataNormalizer->normalize($data);
        }
    }
}
