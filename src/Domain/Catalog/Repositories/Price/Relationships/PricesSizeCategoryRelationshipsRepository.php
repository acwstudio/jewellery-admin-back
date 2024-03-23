<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PricesSizeCategoryRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): Model
    {
        return Price::findOrFail(data_get($params, 'id'))->sizeCategory()->first();
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }
}
