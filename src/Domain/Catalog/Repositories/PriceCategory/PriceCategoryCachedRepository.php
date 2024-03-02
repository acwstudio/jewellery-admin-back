<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\PriceCategory;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class PriceCategoryCachedRepository extends AbstractCachedRepository implements PriceCategoryRepositoryInterface
{
    public function __construct(
        public PriceCategoryRepositoryInterface $priceCategoryRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([PriceCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->priceCategoryRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|PriceCategory
    {
        return Cache::tags([PriceCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->priceCategoryRepositoryInterface->show($id, $data);
            });
    }
}
