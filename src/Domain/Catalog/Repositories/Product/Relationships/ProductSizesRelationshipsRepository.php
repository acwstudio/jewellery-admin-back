<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ProductSizesRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): LengthAwarePaginator|Model
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return Product::findOrFail($id)->sizes()->select()
            ->addSelect('*', DB::raw('(SELECT name FROM size_categories as sc
            where sizes.size_category_id = sc.id) as size_category_name'))
            ->paginate($perPage)->appends($params);
    }

    public function update(array $data): void
    {
        // one-to-many
        $ids = data_get($data, 'data.*.id');
        $collection = Size::whereIn('id', $ids)->get();

        /** @var Size $model */
        foreach ($collection as $model) {
            $model->update([
                'product_id' => data_get($data, 'id')
            ]);
        }
    }
}
