<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypeDevice;

use Domain\AbstractCRUDService;
use Domain\Performance\Pipelines\TypeDevice\TypeDevicePipeline;
use Domain\Performance\Repositories\TypeDevice\TypeDeviceRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypeDeviceService extends AbstractCRUDService
{
    public function __construct(
        public TypeDeviceRepositoryInterface $typeDeviceRepositoryInterface,
        public TypeDevicePipeline $typeDevicePipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->typeDeviceRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->typeDevicePipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->typeDeviceRepositoryInterface->show($id, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        $this->typeDevicePipeline->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        $this->typeDevicePipeline->destroy($id);
    }
}
