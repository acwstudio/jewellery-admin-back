<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\PriceCategory\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class PriceCategoriesSizesRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): LengthAwarePaginator
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return PriceCategory::findOrFail($id)->sizes()->select()
            ->addSelect(DB::raw("(SELECT name FROM size_categories as sc
            where sc.id = sizes.size_category_id) as size_category_name"))
            ->addSelect(DB::raw("(SELECT name FROM products as p
            where p.id = sizes.product_id) as product_name"))
            ->paginate($perPage)->appends($params);
    }

    public function update(array $data): void
    {
        // many-to-many
        $ids = data_get($data, 'data.*.id');
        $id = data_get($data, 'id');

        $model = PriceCategory::findOrFail($id);

        $model->sizes()->sync([15.16]);
    }
}
