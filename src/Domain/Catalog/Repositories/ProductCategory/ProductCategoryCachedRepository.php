<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\ProductCategory;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\ProductCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class ProductCategoryCachedRepository extends AbstractCachedRepository implements ProductCategoryRepositoryInterface
{
    public function __construct(
        public ProductCategoryRepositoryInterface $productCategoryRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([ProductCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->productCategoryRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|ProductCategory
    {
        return Cache::tags([ProductCategory::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->productCategoryRepositoryInterface->show($id, $data);
            });
    }
}
