<?php

declare(strict_types=1);

namespace Domain\Performance\Services\ImageBanner;

use Domain\Performance\Repositories\ImageBanner\ImageBannerRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ImageBannerRelationsService
{
    public function __construct(public ImageBannerRelationsRepository $imageBannerRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->imageBannerRelationsRepository->indexRelations($data);
    }

    /**
     * @param array $data
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->imageBannerRelationsRepository->updateRelations($data);
    }
}
