<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class ProductsWeavesRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): LengthAwarePaginator|Model
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return Product::findOrFail($id)->weaves()->paginate($perPage)->appends($params);
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }
}
