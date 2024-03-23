<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PricesProductRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): Model
    {
        $id = data_get($params, 'id');
        unset($params['id']);

        return Price::findOrFail($id)->size->product->select()
            ->addSelect(DB::raw("(select name from product_categories as pc
            where pc.id = products.product_category_id) as product_category_name"))
            ->first();
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }
}
