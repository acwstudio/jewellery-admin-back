<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\Banner;

use Domain\AbstractCachedRepository;
use Domain\Performance\Models\Banner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class BannerCachedRepository  extends AbstractCachedRepository implements BannerRepositoryInterface
{
    public function __construct(
        public BannerRepositoryInterface $bannerRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([Banner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($data) {
                return $this->bannerRepositoryInterface->index($data);
            }
        );
    }

    public function show(int $id, array $data): Model|Banner
    {
        return Cache::tags([Banner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($id, $data) {
                return $this->bannerRepositoryInterface->show($id, $data);
            }
        );
    }
}
