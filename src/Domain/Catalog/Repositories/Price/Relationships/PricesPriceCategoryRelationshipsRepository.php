<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PricesPriceCategoryRelationshipsRepository extends AbstractRelationshipsRepository
{
    public function index(array $params): Model
    {
        $id = data_get($params, 'id');
        unset($params['id']);

        return Price::findOrFail($id)->priceCategory()->firstOrFail();
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }
}
