<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PricesSizeRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): Model
    {
        return Price::find(data_get($params, 'id'))->size()->select()
            ->addSelect(DB::raw("(select name from size_categories as sc
            where sc.id = sizes.size_category_id) as size_category_name"))
            ->addSelect(DB::raw("(select name from products as p
            where p.id = sizes.product_id) as product_name"))
            ->first();
    }

    public function update(array $data): void
    {
        $sizeId = data_get($data, 'data.id');
        $model = Price::findOrFail(data_get($data, 'id'));

        $model->update([
            'size_id' => (int) $sizeId
        ]);
    }
}
