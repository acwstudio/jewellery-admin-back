<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;

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
        $priceCategoryId = data_get($data, 'data.id');

        $model = Price::findOrFail(data_get($data, 'id'));

        $model->update([
            'price_category_id' => $priceCategoryId
        ]);
    }
}
