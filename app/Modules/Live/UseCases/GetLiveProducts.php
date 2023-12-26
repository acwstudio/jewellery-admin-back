<?php

declare(strict_types=1);

namespace App\Modules\Live\UseCases;

use App\Modules\Live\Enums\LiveProductSortColumnEnum;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\Live\Services\LiveProductService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Live\LiveProduct\GetLiveProductListData;
use App\Packages\DataObjects\Live\LiveProduct\LiveProductListData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;

class GetLiveProducts
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly LiveProductService $liveProductService
    ) {
    }

    public function __invoke(GetLiveProductListData $data): LiveProductListData
    {
        $liveProducts = $this->liveProductService->all();
        $liveProductIds = $liveProducts->pluck('product_id');

        $pagination = new PaginationData(
            page: $data->pagination?->page,
            per_page: $data->pagination?->per_page
        );

        $products = $this->getProducts(
            $liveProductIds,
            $pagination,
            $data->sort_by,
            $data->sort_order
        );

        $products->items->each(function (ProductData $product) use ($liveProducts, $data) {
            $liveProduct = $liveProducts->firstWhere('product_id', $product->id);

            $this->appendLiveContext(
                $product,
                $liveProduct,
                $data->filter?->on_live ?? false
            );
        });

        $productDataCollection = $this->sortProductListData($products, $data->sort_by, $liveProductIds->toArray());

        return new LiveProductListData(
            ProductData::collection($productDataCollection),
            $products->pagination
        );
    }

    private function appendLiveContext(ProductData $product, LiveProduct $liveProduct, bool $forOnLive = false): void
    {
        $context = [
            'on_live' => $liveProduct->on_live
        ];

        if ($forOnLive) {
            $context['live_number'] = $liveProduct->number;
        }

        $product->additional($context);
    }

    private function getProducts(
        Collection $liveProductIds,
        PaginationData $pagination,
        ?LiveProductSortColumnEnum $liveProductSortColumn,
        ?SortOrderEnum $sortOrder
    ): ProductListData {
        return $this->catalogModuleClient->getProducts(
            $this->getProductGetListData($liveProductIds, $pagination, $liveProductSortColumn, $sortOrder)
        );
    }

    private function getProductGetListData(
        Collection $liveProductIds,
        PaginationData $pagination,
        ?LiveProductSortColumnEnum $liveProductSortColumn,
        ?SortOrderEnum $sortOrder
    ): ProductGetListData {
        if ($liveProductSortColumn === null || $sortOrder === null) {
            return $this->getProductGetListDataUnsorted($liveProductIds, $pagination);
        }

        return $this->getProductGetListDataSorted(
            $liveProductIds,
            $pagination,
            $liveProductSortColumn,
            $sortOrder
        );
    }

    private function getProductGetListDataUnsorted(
        Collection $liveProductIds,
        PaginationData $pagination
    ): ProductGetListData {
        return new ProductGetListData(
            pagination: $pagination,
            filter: new FilterProductData(
                ids: $liveProductIds->toArray(),
                offer_price_type: OfferPriceTypeEnum::LIVE->value
            )
        );
    }

    private function getProductGetListDataSorted(
        Collection $liveProductIds,
        PaginationData $pagination,
        LiveProductSortColumnEnum $liveProductSortColumn,
        SortOrderEnum $sortOrder
    ): ProductGetListData {
        $sortBy = match ($liveProductSortColumn) {
            default => ProductSortColumnEnum::POPULARITY
        };

        return new ProductGetListData(
            sort_by: $sortBy,
            sort_order: $sortOrder,
            pagination: $pagination,
            filter: new FilterProductData(
                ids: $liveProductIds->toArray(),
                offer_price_type: OfferPriceTypeEnum::LIVE->value
            )
        );
    }

    private function sortProductListData(
        ProductListData $productListData,
        ?LiveProductSortColumnEnum $sortColumn,
        array $data = []
    ): Enumerable {
        $productCollection = $productListData->items->toCollection();

        return match ($sortColumn) {
            LiveProductSortColumnEnum::STARTED_AT => $productCollection->sortBy(
                fn(ProductData $productData) => array_search($productData->id, $data)
            )->flatten(),
            LiveProductSortColumnEnum::NUMBER => $productCollection->sortBy(
                fn(ProductData $productData) => $productData->getAdditionalData()['live_number'] ?? 0
            )->flatten(),
            default => $productCollection
        };
    }
}
