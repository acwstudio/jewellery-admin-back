<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Product;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\Filter\FilterLiveProductData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Illuminate\Support\Collection;

class GetLiveProductIds
{
    public function __construct(
        private readonly LiveModuleClientInterface $liveModuleClient
    ) {
    }

    /**
     * @param Collection<Product> $products
     * @return array
     */
    public function __invoke(Collection $products): array
    {
        if ($products->isEmpty()) {
            return [];
        }

        $ids = $products->pluck('id')->toArray();

        $liveProducts = $this->liveModuleClient->getShortLiveProducts(new GetLiveProductListData(
            pagination: new PaginationData(1, $products->count()),
            filter: new FilterLiveProductData(is_active: true, ids: $ids)
        ));

        return $liveProducts->items->toCollection()->pluck('product_id')->toArray();
    }
}
