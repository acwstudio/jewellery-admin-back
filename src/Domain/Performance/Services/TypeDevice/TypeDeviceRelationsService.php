<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypeDevice;

use Domain\Performance\Repositories\TypeDevice\TypeDeviceRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypeDeviceRelationsService
{
    public function __construct(public TypeDeviceRelationsRepository $typeDeviceRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->typeDeviceRelationsRepository->indexRelations($data);
    }

    /**
     * @param array $data
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->typeDeviceRelationsRepository->updateRelations($data);
    }
}
