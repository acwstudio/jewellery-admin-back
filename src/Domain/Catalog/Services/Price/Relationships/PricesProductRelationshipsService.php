<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price\Relationships;

use Domain\Catalog\Repositories\Price\Relationships\PricesProductRelationshipsRepository;
use Illuminate\Database\Eloquent\Model;

final class PricesProductRelationshipsService
{
    public function __construct(protected PricesProductRelationshipsRepository $repository)
    {
    }

    public function index(array $params): Model
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
