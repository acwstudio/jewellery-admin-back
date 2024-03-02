<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypePage;

use Domain\Performance\Repositories\TypePage\TypePageRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypePageRelationsService
{
    public function __construct(public TypePageRelationsRepository $typePageRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->typePageRelationsRepository->indexRelations($data);
    }

    /**
     * @param array $data
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->typePageRelationsRepository->updateRelations($data);
    }
}
