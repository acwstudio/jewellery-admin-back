<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Services;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Repositories\SaleRepository;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleBlueprint;
use App\Modules\Promotions\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SaleService
{
    public function __construct(
        private readonly SaleRepository $saleRepository
    ) {
    }

    public function getSale(int $id): Sale
    {
        return $this->saleRepository->getById($id, true);
    }

    public function getSaleBySlug(string $slug): Sale
    {
        return $this->saleRepository->getBySlug($slug, true);
    }

    public function getSaleByPromotion(Promotion $promotion): ?Sale
    {
        return $this->saleRepository->getByPromotionId($promotion->id);
    }

    public function getSales(Pagination $pagination): LengthAwarePaginator
    {
        return $this->saleRepository->getListByPagination($pagination);
    }

    public function create(Promotion $promotion, SaleBlueprint $blueprint): Sale
    {
        return $this->saleRepository->create($promotion, $blueprint);
    }
}
