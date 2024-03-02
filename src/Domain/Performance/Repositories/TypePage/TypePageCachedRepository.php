<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypePage;

use Domain\AbstractCachedRepository;
use Domain\Performance\Models\TypePage;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class TypePageCachedRepository extends AbstractCachedRepository implements TypePageRepositoryInterface
{
    public function __construct(
        public TypePageRepositoryInterface $typePageRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([TypePage::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($data) {
                return $this->typePageRepositoryInterface->index($data);
            }
        );
    }

    public function show(int $id, array $data): Model|TypePage
    {
        return Cache::tags([TypePage::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($id, $data) {
                return $this->typePageRepositoryInterface->show($id, $data);
            }
        );
    }
}
