<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ProductsProductCategoryRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): LengthAwarePaginator|Model
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return Product::findOrFail($id)->productCategory()->select()
            ->addSelect('*', DB::raw('(SELECT name FROM product_categories as pc
            where product_categories.parent_id = pc.id) as parent_name'))
            ->firstOrFail();
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }
}
