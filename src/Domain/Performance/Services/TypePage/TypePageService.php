<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypePage;

use Domain\AbstractCRUDService;
use Domain\Performance\Pipelines\TypePage\TypePagePipeline;
use Domain\Performance\Repositories\TypePage\TypePageRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypePageService extends AbstractCRUDService
{
    public function __construct(
        public TypePageRepositoryInterface $typePageRepositoryInterface,
        public TypePagePipeline $typePagePipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->typePageRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->typePagePipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->typePageRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->typePagePipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->typePagePipeline->destroy($id);
    }
}
