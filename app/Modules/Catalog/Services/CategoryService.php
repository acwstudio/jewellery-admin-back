<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Repositories\CategoryRepository;
use App\Modules\Catalog\Repositories\PreviewImageRepository;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Modules\Catalog\Support\SlugGenerator;
use App\Packages\Events\CategoryCreated;
use App\Packages\Exceptions\CircularRelationException;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        protected readonly CategoryRepository $categoryRepository,
        protected readonly SlugGenerator $slugGenerator,
        protected readonly PreviewImageRepository $previewImageRepository,
    ) {
    }

    public function getCategory(int $id): ?Category
    {
        return $this->categoryRepository->getCategory($id);
    }

    public function getCategories(CategoryFilter $filter): Collection
    {
        return $this->categoryRepository->getCategories($filter);
    }

    public function createCategory(
        CategoryBlueprint $categoryBlueprint,
        Category|int|null $parent = null,
        PreviewImage|int|null $previewImage = null
    ): Category {
        if (is_int($parent)) {
            $parent = $this->categoryRepository->getCategory($parent, true);
        }

        if (is_int($previewImage)) {
            $previewImage = $this->previewImageRepository->getById($previewImage, true);
        }

        if (null === $categoryBlueprint->getSlug()) {
            $slug = $this->slugGenerator->createWithParent(
                $categoryBlueprint->getTitle(),
                $parent
            );

            $categoryBlueprint->setSlug($slug);
        }

        $category = $this->categoryRepository->createCategory(
            $categoryBlueprint,
            $parent,
            $previewImage
        );

        CategoryCreated::dispatch($category->id);

        return $category;
    }

    /**
     * @throws CircularRelationException
     */
    public function updateCategory(
        Category|int $category,
        CategoryBlueprint $categoryBlueprint,
        Category|int|null $parent = null,
        PreviewImage|int|null $previewImage = null
    ): Category {
        if (is_int($category)) {
            $category = $this->categoryRepository->getCategory($category, true);
        }

        if (is_int($parent)) {
            $parent = $this->categoryRepository->getCategory($parent, true);
        }

        if (is_int($previewImage)) {
            $previewImage = $this->previewImageRepository->getById($previewImage, true);
        }

        if (null === $categoryBlueprint->getSlug()) {
            $categoryBlueprint->setSlug(
                $this->slugGenerator->create($category)
            );
        }

        return $this->categoryRepository->updateCategory(
            $category,
            $categoryBlueprint,
            $parent,
            $previewImage
        );
    }

    public function deleteCategory(int $id): void
    {
        $category = $this->categoryRepository->getCategory($id);
        $this->categoryRepository->deleteCategory($category);
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->getCategoryBySlug($slug, true);
    }
}
