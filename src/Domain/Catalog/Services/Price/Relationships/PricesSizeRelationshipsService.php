<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price\Relationships;

use Domain\Catalog\Repositories\Price\Relationships\PricesSizeRelationshipsRepository;
use Illuminate\Database\Eloquent\Model;

final class PricesSizeRelationshipsService
{
    public function __construct(protected PricesSizeRelationshipsRepository $repository)
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
