<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypeBanner;

use Domain\Performance\Repositories\TypeBanner\TypeBannerRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypeBannerRelationsService
{
    public function __construct(public TypeBannerRelationsRepository $typeBannerRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->typeBannerRelationsRepository->indexRelations($data);
    }

    /**
     * @param array $data
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->typeBannerRelationsRepository->updateRelations($data);
    }
}
