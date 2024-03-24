<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Price\Relationships\PricesPriceCategoryRelationshipsRepository;
use Illuminate\Database\Eloquent\Model;

final class PricesPriceCategoryRelationshipsService extends AbstractRelationshipsService
{
    public function __construct(protected PricesPriceCategoryRelationshipsRepository $repository)
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
