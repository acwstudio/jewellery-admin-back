<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class PriceCachedRepository extends AbstractCachedRepository implements PriceRepositoryInterface
{
    public function __construct(
        public PriceRepositoryInterface $priceRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Price::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->priceRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|Price
    {
        return Cache::tags([Price::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->priceRepositoryInterface->show($id, $data);
            });
    }
}
