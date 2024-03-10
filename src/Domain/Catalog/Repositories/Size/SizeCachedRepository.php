<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Size;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class SizeCachedRepository extends AbstractCachedRepository implements SizeRepositoryInterface
{
    public function __construct(
        public SizeRepositoryInterface $sizeRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Size::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->sizeRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|Size
    {
        return Cache::tags([Size::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->sizeRepositoryInterface->show($id, $data);
            });
    }
}
