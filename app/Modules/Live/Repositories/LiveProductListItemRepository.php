<?php

declare(strict_types=1);

namespace App\Modules\Live\Repositories;

use App\Modules\Live\Models\LiveProductListItem;
use App\Modules\Live\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LiveProductListItemRepository
{
    public function getById(int $id, bool $fail = false): ?LiveProductListItem
    {
        if ($fail) {
            return LiveProductListItem::findOrFail($id);
        }

        return LiveProductListItem::find($id);
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $models = LiveProductListItem::query()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $models->total() === 0) {
            throw (new ModelNotFoundException())->setModel(LiveProductListItem::class);
        }

        return $models;
    }
}
