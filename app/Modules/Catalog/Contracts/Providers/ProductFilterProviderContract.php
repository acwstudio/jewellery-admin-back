<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Providers;

use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;

interface ProductFilterProviderContract
{
    public function get(
        int $position,
        bool $forStatic = false,
        ?GetListProductFilterData $data = null
    ): CatalogProductFilterData;

    public function isStatic(): bool;
}
