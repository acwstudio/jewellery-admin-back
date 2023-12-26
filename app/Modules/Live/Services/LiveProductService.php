<?php

declare(strict_types=1);

namespace App\Modules\Live\Services;

use App\Modules\Live\Models\LiveProduct;
use App\Modules\Live\Repositories\LiveProductRepository;
use App\Modules\Live\Support\Blueprints\LiveProductBlueprint;
use App\Modules\Live\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LiveProductService
{
    public function __construct(
        private readonly LiveProductRepository $liveProductRepository,
    ) {
    }

    public function all(): Collection
    {
        return $this->liveProductRepository->getList();
    }

    public function getLiveProductByProductId(int $productId, bool $fail = false): ?LiveProduct
    {
        return $this->liveProductRepository->getByProductId($productId, $fail);
    }

    public function getLiveProducts(Pagination $pagination): LengthAwarePaginator
    {
        return $this->liveProductRepository->getPaginatedList($pagination);
    }

    public function createLiveProduct(LiveProductBlueprint $liveProductBlueprint): LiveProduct
    {
        return $this->liveProductRepository->create($liveProductBlueprint);
    }

    public function createOrUpdateLiveProduct(LiveProductBlueprint $liveProductBlueprint): LiveProduct
    {
        return $this->liveProductRepository->createOrUpdate($liveProductBlueprint);
    }

    public function unsetOnLiveProducts(): void
    {
        $this->liveProductRepository->unsetOnLive();
    }

    public function updateOnLiveAndNumber(LiveProduct $liveProduct, int $number = 0): void
    {
        $this->liveProductRepository->setOnLiveAndNumber($liveProduct, $number);
    }
}
