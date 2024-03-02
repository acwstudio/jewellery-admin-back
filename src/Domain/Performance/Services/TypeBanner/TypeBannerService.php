<?php

declare(strict_types=1);

namespace Domain\Performance\Services\TypeBanner;

use Domain\AbstractCRUDService;
use Domain\Performance\Pipelines\TypeBanner\TypeBannerPipeline;
use Domain\Performance\Repositories\TypeBanner\TypeBannerRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class TypeBannerService extends AbstractCRUDService
{
    public function __construct(
        public TypeBannerRepositoryInterface $typeBannerRepositoryInterface,
        public TypeBannerPipeline $typeBannerPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->typeBannerRepositoryInterface->index($data);
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        return $this->typeBannerPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->typeBannerRepositoryInterface->show($id, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        $this->typeBannerPipeline->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        $this->typeBannerPipeline->destroy($id);
    }
}
