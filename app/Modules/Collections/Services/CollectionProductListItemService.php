<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\CollectionProductListItem;
use App\Modules\Collections\Repository\CollectionProductListItemRepository;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CollectionProductListItemService
{
    public function __construct(
        private readonly CollectionProductListItemRepository $collectionProductListItemRepository
    ) {
    }

    public function get(int $id): CollectionProductListItem
    {
        return $this->collectionProductListItemRepository->getById($id, true);
    }

    public function getList(Pagination $pagination): LengthAwarePaginator
    {
        return $this->collectionProductListItemRepository->getList($pagination);
    }
}
