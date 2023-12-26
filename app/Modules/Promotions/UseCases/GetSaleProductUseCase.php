<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Modules\Sales\Services\SaleProductService;
use App\Modules\Promotions\Support\Pagination;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData as CatalogFilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData as CatalogProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\CatalogProductListData;
use App\Packages\DataObjects\Promotions\Sales\CatalogProduct\GetCatalogProductListData;
use App\Packages\DataObjects\Promotions\Sales\Filter\FilterSaleProductData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\GetSaleProductListData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\SaleProductListData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;

class GetSaleProductUseCase
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly SaleProductService $saleProductService
    ) {
    }

    public function getList(GetSaleProductListData $data): SaleProductListData
    {
        $paginator = $this->saleProductService->getPaginator(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return SaleProductListData::fromPaginator($paginator);
    }

    public function getListByCatalog(GetCatalogProductListData $data): CatalogProductListData
    {
        $this->createGetSaleProductListData($data);
        $saleProducts = $this->saleProductService->getAll();
        $saleProductIds = $saleProducts->pluck('product_id');

        if ($saleProductIds->isEmpty()) {
            return new CatalogProductListData(
                CatalogProductData::collection([]),
                new PaginationData(1, 32, 0, 1)
            );
        }

        $products = $this->getProducts($saleProductIds, $data);

        return new CatalogProductListData($products->items, $products->pagination);
    }

    private function getProducts(Collection $saleProductIds, GetCatalogProductListData $data): ProductListData
    {
        return $this->catalogModuleClient->getProducts(
            $this->getProductGetListData($saleProductIds, $data)
        );
    }

    private function getProductGetListData(
        Collection $saleProductIds,
        GetCatalogProductListData $data
    ): ProductGetListData {
        $filters = $data->filter?->toArray();
        $filters['ids'] = $saleProductIds->toArray();
        $filters['in_stock'] = true;
        $filters['is_active'] = true;
        $filters['has_image'] = true;

        return new ProductGetListData(
            sort_by: $data->sort_by,
            sort_order: $data->sort_order,
            pagination: $data->pagination,
            filter: CatalogFilterProductData::from($filters)
        );
    }

    private function createGetSaleProductListData(GetCatalogProductListData $data): GetSaleProductListData
    {
        return new GetSaleProductListData(
            filter: new FilterSaleProductData(
                sale_id: $data->sale_id,
                sale_slug: $data->sale_slug,
                is_active: (null === $data->is_active) ? true : $data->is_active
            )
        );
    }
}
