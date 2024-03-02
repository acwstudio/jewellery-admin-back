<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\Price\PricePipeline;
use Domain\Catalog\Repositories\Price\PriceRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class PriceService extends AbstractCRUDService
{
    public function __construct(
        public PriceRepositoryInterface $priceRepositoryInterface,
        public PricePipeline $pricePipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->priceRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->pricePipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->priceRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->pricePipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->pricePipeline->destroy($id);
    }
}
