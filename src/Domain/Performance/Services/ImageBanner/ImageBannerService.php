<?php

declare(strict_types=1);

namespace Domain\Performance\Services\ImageBanner;

use Domain\AbstractCRUDService;
use Domain\Performance\Pipelines\ImageBanner\ImageBannerPipeline;
use Domain\Performance\Repositories\ImageBanner\ImageBannerRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ImageBannerService extends AbstractCRUDService
{
    public function __construct(
        public ImageBannerRepositoryInterface $imageBannerRepositoryInterface,
        public ImageBannerPipeline $imageBannerPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->imageBannerRepositoryInterface->index($data);
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        return $this->imageBannerPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->imageBannerRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->imageBannerPipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->imageBannerPipeline->destroy($id);
    }
}
