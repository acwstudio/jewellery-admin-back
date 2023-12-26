<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Models\CollectionProductListItem;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CollectionProductListItemRepository
{
    public function getById(int $id, bool $fail = false): ?CollectionProductListItem
    {
        if ($fail) {
            return CollectionProductListItem::findOrFail($id);
        }

        return CollectionProductListItem::find($id);
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $models = CollectionProductListItem::query()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $models->total() === 0) {
            throw (new ModelNotFoundException())->setModel(CollectionProductListItem::class);
        }

        return $models;
    }
}
