<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Weave;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\Weave\WeavePipeline;
use Domain\Catalog\Repositories\Weave\WeaveRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class WeaveService extends AbstractCRUDService
{
    public function __construct(
        public WeaveRepositoryInterface $weaveRepositoryInterface,
        public WeavePipeline $weavePipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->weaveRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->weavePipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->weaveRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->weavePipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->weavePipeline->destroy($id);
    }
}
