<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\PriceCategory;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\PriceCategory\PriceCategoryPipeline;
use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class PriceCategoryService extends AbstractCRUDService
{
    public function __construct(
        public PriceCategoryRepositoryInterface $priceCategoryRepositoryInterface,
        public PriceCategoryPipeline $priceCategoryPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->priceCategoryRepositoryInterface->index($data);
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        return $this->priceCategoryPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->priceCategoryRepositoryInterface->show($id, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        $this->priceCategoryPipeline->update($data);
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        $this->priceCategoryPipeline->destroy($id);
    }
}
