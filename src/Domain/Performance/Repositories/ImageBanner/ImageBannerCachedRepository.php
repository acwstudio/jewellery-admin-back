<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\ImageBanner;

use Domain\AbstractCachedRepository;
use Domain\Performance\Models\ImageBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class ImageBannerCachedRepository extends AbstractCachedRepository implements ImageBannerRepositoryInterface
{
    public function __construct(
        public ImageBannerRepositoryInterface $imageBannerRepositoryInterface
    ) {
    }

    public function index(array $data): Paginator
    {
        return Cache::tags([ImageBanner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($data) {
                return $this->imageBannerRepositoryInterface->index($data);
            }
        );
    }

    public function show(int $id, array $data): Model|ImageBanner
    {
        return Cache::tags([ImageBanner::class])->remember(
            $this->getCacheKey($data),
            $this->getTtl(),
            function () use ($id, $data) {
                return $this->imageBannerRepositoryInterface->show($id, $data);
            }
        );
    }
}
