<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;


use App\Modules\Catalog\Models\CategoryListItem;
use App\Modules\Catalog\Repositories\CategoryListRepository;
use Illuminate\Support\Collection;

class CategoryListService
{
    public function __construct(
        private readonly CategoryListRepository $categoryListRepository
    ) {
    }

    public function getCategoryListItem(int $id): CategoryListItem
    {
        return $this->categoryListRepository->getCategoryListItem($id, true);
    }

    public function getCategoryList(): Collection
    {
        return $this->categoryListRepository->getCategoryList();
    }

    public function getCategoryListItemBySlug(string $slug): CategoryListItem
    {
        return $this->categoryListRepository->getCategoryListBySlug($slug, true);
    }
}
