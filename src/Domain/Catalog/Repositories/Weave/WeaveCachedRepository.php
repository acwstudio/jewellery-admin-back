<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Weave;

use Domain\AbstractCachedRepository;
use Domain\Catalog\Models\Weave;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class WeaveCachedRepository extends AbstractCachedRepository implements WeaveRepositoryInterface
{
    public function __construct(
        public WeaveRepositoryInterface $weaveRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Weave::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($data) {
                return $this->weaveRepositoryInterface->index($data);
            });
    }

    public function show(int $id, array $data): Model|Weave
    {
        return Cache::tags([Weave::class])->remember($this->getCacheKey($data), $this->getTtl(),
            function () use ($id, $data) {
                return $this->weaveRepositoryInterface->show($id, $data);
            });
    }
}
