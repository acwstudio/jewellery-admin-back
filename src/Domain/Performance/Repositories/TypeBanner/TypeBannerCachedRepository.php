<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypeBanner;

use Domain\AbstractCachedRepository;
use Domain\Performance\Models\TypeBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class TypeBannerCachedRepository extends AbstractCachedRepository implements TypeBannerRepositoryInterface
{
    public function __construct(
        public TypeBannerRepositoryInterface $typeBannerRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([TypeBanner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($data) {
                return $this->typeBannerRepositoryInterface->index($data);
            }
        );
    }

    public function show(int $id, array $data): Model|TypeBanner
    {
        return Cache::tags([TypeBanner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($id, $data) {
                return $this->typeBannerRepositoryInterface->show($id, $data);
            }
        );
    }
}
