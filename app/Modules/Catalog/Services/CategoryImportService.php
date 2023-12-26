<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use App\Packages\DataObjects\Catalog\Category\ImportCategoryData;
use Generator;

class CategoryImportService
{
    public function __construct(
        protected DataProviderInterface $categoryDataProvider,
        protected DataNormalizerInterface $categoryDataNormalizer
    ) {
    }

    /**
     * @return Generator<ImportCategoryData>
     */
    public function import(): Generator
    {
        foreach ($this->categoryDataProvider->getRawData() as $data) {
            yield $this->categoryDataNormalizer->normalize($data);
        }
    }
}
