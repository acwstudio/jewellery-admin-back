<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\CategorySlugAlias;
use App\Modules\Catalog\Services\CategoryImportService;
use App\Modules\Catalog\Services\CategoryService;
use App\Modules\Catalog\Services\CategorySlugAliasService;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Packages\DataObjects\Catalog\Category\ImportCategoryData;
use App\Packages\Events\Sync\CategoriesImported;
use App\Packages\Exceptions\CircularRelationException;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class ImportCategories
{
    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryImportService $categoryImportService,
        protected CategorySlugAliasService $categorySlugAliasService,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @throws CircularRelationException
     */
    public function __invoke(?callable $onEach = null): void
    {
        foreach ($this->categoryImportService->import() as $data) {
            try {
                DB::transaction(function () use ($data) {
                    $this->upsertCategory($data);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Category with extID: $data->external_id import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }

        CategoriesImported::dispatch();
    }

    /**
     * @throws CircularRelationException
     */
    private function upsertCategory(ImportCategoryData $data): void
    {
        $id = $this->getCategoryIdByExternalId($data->external_id);

        $blueprint = new CategoryBlueprint(
            $data->title,
            $data->h1,
            $data->description,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            $data->external_id
        );

        $parentId = null;

        if ($this->hasParent($data->external_parent_id)) {
            $parentId = $this->getCategoryIdByExternalIdOrFail($data->external_parent_id);
        }

        if (null === $id) {
            $category = $this->categoryService->createCategory(
                $blueprint,
                $parentId
            );
        } else {
            $category = $this->categoryService->getCategory($id);

            $this->categoryService->updateCategory(
                $category,
                $blueprint,
                $parentId
            );
        }

        if (!$this->isCategoryHasSlugAlias($category, $data->slug)) {
            $this->categorySlugAliasService->createCategorySlugAlias(
                $category,
                $data->slug
            );
        }
    }

    private function hasParent(?string $externalParentId = null): bool
    {
        return !empty($externalParentId);
    }

    private function getCategoryIdByExternalId(string $externalId): ?int
    {
        return
            $this->categoryService
                ->getCategories(
                    new CategoryFilter(external_id: $externalId)
                )
                ->first()?->id;
    }

    private function getCategoryIdByExternalIdOrFail(string $externalId): int
    {
        return
            $this->categoryService
                ->getCategories(
                    new CategoryFilter(external_id: $externalId)
                )
                ->firstOrFail()->id;
    }

    private function isCategoryHasSlugAlias(Category $category, string $slug): bool
    {
        return $category->slugAliases->contains(function (CategorySlugAlias $categorySlugAlias) use ($slug) {
            return $categorySlugAlias->slug === $slug;
        });
    }
}
