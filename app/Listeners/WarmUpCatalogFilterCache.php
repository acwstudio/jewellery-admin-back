<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Modules\Catalog\Services\CatalogProductFilterService;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use Illuminate\Support\Facades\Cache;

class WarmUpCatalogFilterCache
{
    public function __construct(
        private readonly CatalogProductFilterService $catalogProductFilterService
    ) {
    }

    public function handle(): void
    {
        Cache::forget('catalog.filter');
        $this->catalogProductFilterService->getList(new GetListProductFilterData());
    }
}
