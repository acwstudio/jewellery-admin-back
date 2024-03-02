<?php

declare(strict_types=1);

namespace Domain\Performance\Services\Banner;

use Domain\Performance\Repositories\Banner\BannerRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BannerRelationsService
{
    public function __construct(public BannerRelationsRepository $bannerRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->bannerRelationsRepository->indexRelations($data);
    }

    /**
     * @param array $data
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->bannerRelationsRepository->updateRelations($data);
    }
}
