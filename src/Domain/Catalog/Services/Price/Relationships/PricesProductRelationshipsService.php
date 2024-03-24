<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Price\Relationships\PricesProductRelationshipsRepository;
use Illuminate\Database\Eloquent\Model;

final class PricesProductRelationshipsService extends AbstractRelationshipsService
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
        // HasOneThrough updating doesn't make sense. You can do something another
    }
}
