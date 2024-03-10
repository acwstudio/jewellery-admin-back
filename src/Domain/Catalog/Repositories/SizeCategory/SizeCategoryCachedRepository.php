<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\SizeCategory;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class SizeCategoryCachedRepository extends AbstractCachedRepository implements SizeCategoryRepositoryInterface
{
    public function __construct(
        public SizeCategoryRepositoryInterface $sizeCategoryRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Size::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->sizeCategoryRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|Size
    {
        return Cache::tags([Size::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->sizeCategoryRepositoryInterface->show($id, $data);
            });
    }
}
