<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class BreadcrumbService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function getCategoryBreadcrumbs(int|Category $category): Collection
    {
        if (!$category instanceof Category) {
            $category = $this->categoryRepository->getCategory($category);
        }

        return $this->flattenCategories($category);
    }

    private function flattenCategories(
        Category $category,
        Collection $breadcrumbs = new Collection()
    ): Collection {
        $breadcrumbs->prepend($category);

        if (is_null($category->parent)) {
            return $breadcrumbs;
        }

        return $this->flattenCategories($category->parent, $breadcrumbs);
    }

    public function getCategoryBreadcrumbsBySlug(string|Category $category): Collection
    {
        if (!$category instanceof Category) {
            $category = $this->categoryRepository->getCategoryBySlug($category);
        }

        return $this->flattenCategories($category);
    }
}
