<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\SizeCategory;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\SizeCategory\SizeCategoryPipeline;
use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class SizeCategoryService extends AbstractCRUDService
{
    public function __construct(
        public SizeCategoryRepositoryInterface $sizeCategoryRepositoryInterface,
        public SizeCategoryPipeline $sizeCategoryPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->sizeCategoryRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        // TODO: Implement store() method.
    }

    public function show(int $id, array $data): Model
    {
        return $this->sizeCategoryRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id): void
    {
        // TODO: Implement destroy() method.
    }
}
