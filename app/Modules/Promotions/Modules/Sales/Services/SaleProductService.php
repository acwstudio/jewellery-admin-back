<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Services;

use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Modules\Promotions\Modules\Sales\Repositories\SaleProductRepository;
use App\Modules\Promotions\Modules\Sales\Repositories\SaleRepository;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleProductBlueprint;
use App\Modules\Promotions\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SaleProductService
{
    public function __construct(
        private readonly SaleProductRepository $saleProductRepository,
        private readonly SaleRepository $saleRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->saleProductRepository->getList();
    }

    public function getPaginator(Pagination $pagination): LengthAwarePaginator
    {
        return $this->saleProductRepository->getListByPagination($pagination);
    }

    public function createOrUpdate(Sale|int $sale, SaleProductBlueprint $blueprint): SaleProduct
    {
        if (is_int($sale)) {
            $sale = $this->saleRepository->getById($sale, true);
        }

        return $this->saleProductRepository->createOrUpdate($sale, $blueprint);
    }

    public function delete(int $id): void
    {
        $model = $this->saleProductRepository->getById($id, true);
        $this->saleProductRepository->delete($model);
    }
}
