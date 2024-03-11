<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Size;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\Size\SizePipeline;
use Domain\Catalog\Repositories\Size\SizeRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class SizeService extends AbstractCRUDService
{
    public function __construct(
        public SizeRepositoryInterface $sizeRepositoryInterface,
        public SizePipeline $sizePipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->sizeRepositoryInterface->index($data);
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        return $this->sizePipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->sizeRepositoryInterface->show($id, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        $this->sizePipeline->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        $this->sizePipeline->destroy($id);
    }
}
