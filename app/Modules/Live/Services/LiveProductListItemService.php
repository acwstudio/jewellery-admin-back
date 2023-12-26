<?php

declare(strict_types=1);

namespace App\Modules\Live\Services;

use App\Modules\Live\Models\LiveProductListItem;
use App\Modules\Live\Repositories\LiveProductListItemRepository;
use App\Modules\Live\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LiveProductListItemService
{
    public function __construct(
        private readonly LiveProductListItemRepository $liveProductListItemRepository,
    ) {
    }

    public function get(int $id): LiveProductListItem
    {
        return $this->liveProductListItemRepository->getById($id, true);
    }

    public function getList(Pagination $pagination): LengthAwarePaginator
    {
        return $this->liveProductListItemRepository->getList($pagination);
    }
}
