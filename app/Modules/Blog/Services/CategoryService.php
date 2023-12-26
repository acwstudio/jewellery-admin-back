<?php

declare(strict_types=1);

namespace App\Modules\Blog\Services;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Repositories\CategoryRepository;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function getCategory(int $id): Category
    {
        $category = $this->categoryRepository->getById($id);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function getCategoryBySlug(string $slug): Category
    {
        $category = $this->categoryRepository->getBySlug($slug);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    public function getCategories(?int $perPage, ?int $currentPage, ?string $column, ?SortOrderEnum $orderBy): LengthAwarePaginator
    {
        return $this->categoryRepository->getList($perPage, $currentPage, $column, $orderBy);
    }

    public function createCategory(string $slug, string $name, ?int $position, ?string $metaDescription): Category
    {
        return $this->categoryRepository->create(
            $slug,
            $name,
            $position,
            $metaDescription
        );
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function updateCategory(int $id, string $slug, string $name, ?int $position, ?string $metaDescription): Category
    {
        $category = $this->categoryRepository->getById($id);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundException();
        }

        $this->categoryRepository->update(
            $category,
            $slug,
            $name,
            $position,
            $metaDescription
        );

        return $category;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function deleteCategory(int $id): bool
    {
        $category = $this->categoryRepository->getById($id);

        if (!$category instanceof Category) {
            throw new CategoryNotFoundException();
        }

        return $this->categoryRepository->delete($category);
    }
}
