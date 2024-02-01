<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class ProductCachedRepository extends AbstractCachedRepository implements ProductRepositoryInterface
{
    public function __construct(
        public ProductRepositoryInterface $productRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Product::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->productRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|Product
    {
        return Cache::tags([Product::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->productRepositoryInterface->show($id, $data);
            });
    }
}
