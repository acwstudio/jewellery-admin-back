<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\PriceCategory\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Price;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class PriceCategoryPricesRelationshipsRepository extends AbstractRelationshipsRepository
{
    public function index(array $params): LengthAwarePaginator
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return PriceCategory::findOrFail($id)->prices()
            ->addSelect("*", DB::raw("(SELECT value FROM sizes where sizes.id = prices.size_id)
            as size_value"))
            ->paginate($perPage)->appends($params);
    }

    public function update(array $data): void
    {
        // one-to-many
        $ids = data_get($data, 'data.*.id');
        $collection = Price::whereIn('id', $ids)->get();

        /** @var Price $model */
        foreach ($collection as $model) {
            $model->update([
                'price_category_id' => data_get($data, 'id')
            ]);
        }
    }
}
