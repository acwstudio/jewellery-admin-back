<?php

declare(strict_types=1);

namespace App\Modules\Promotions\UseCases;

use App\Modules\Promotions\Modules\Sales\Services\SaleService;
use App\Modules\Promotions\Support\Pagination;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleData;
use App\Packages\DataObjects\Promotions\Sales\Sale\SaleListData;

class GetSaleUseCase
{
    public function __construct(
        private readonly SaleService $saleService
    ) {
    }

    public function getById(int $id): SaleData
    {
        $model = $this->saleService->getSale($id);
        return SaleData::fromModel($model);
    }

    public function getBySlug(string $slug): SaleData
    {
        $model = $this->saleService->getSaleBySlug($slug);
        return SaleData::fromModel($model);
    }

    public function getList(?PaginationData $data = null): SaleListData
    {
        $paginator = $this->saleService->getSales(
            new Pagination(
                $data?->page,
                $data?->per_page
            )
        );
        return SaleListData::fromPaginator($paginator);
    }
}
