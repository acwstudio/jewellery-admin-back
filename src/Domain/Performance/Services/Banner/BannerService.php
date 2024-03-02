<?php

declare(strict_types=1);

namespace Domain\Performance\Services\Banner;

use Domain\AbstractCRUDService;
use Domain\Performance\Pipelines\Banner\BannerPipeline;
use Domain\Performance\Repositories\Banner\BannerRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class BannerService extends AbstractCRUDService
{
    public function __construct(
        public BannerRepositoryInterface $bannerRepositoryInterface,
        public BannerPipeline $bannerPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->bannerRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->bannerPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->bannerRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->bannerPipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->bannerPipeline->destroy($id);
    }
}
