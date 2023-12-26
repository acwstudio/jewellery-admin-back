<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Packages\Exceptions\CircularRelationException;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public function getCategory(int $id, bool $fail = false): ?Category
    {
        if ($fail) {
            return Category::findOrFail($id);
        }

        return Category::find($id);
    }

    /**
     * @param CategoryFilter $filter
     * @param bool $fail
     * @return Collection<Category>
     */
    public function getCategories(CategoryFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(Category::query())->withFilter($filter)->create();

        /** @var Collection $categories */
        $categories = $query->get();

        if ($fail && $categories->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $categories;
    }

    public function createCategory(
        CategoryBlueprint $categoryBlueprintData,
        ?Category $parent = null,
        ?PreviewImage $previewImage = null
    ): Category {
        $category = new Category([
            'title' => $categoryBlueprintData->getTitle(),
            'h1' => $categoryBlueprintData->getH1(),
            'description' => $categoryBlueprintData->getDescription(),
            'meta_title' => $categoryBlueprintData->getMetaTitle(),
            'meta_description' => $categoryBlueprintData->getMetaDescription(),
            'meta_keywords' => $categoryBlueprintData->getMetaKeywords(),
            'external_id' => $categoryBlueprintData->getExternalId(),
            'slug' => $categoryBlueprintData->getSlug()
        ]);

        if (!is_null($parent)) {
            $category->parent()->associate($parent);
        }

        $category->previewImage()->associate($previewImage);

        $category->save();
        $category->refresh();

        return $category;
    }

    /**
     * @throws CircularRelationException
     */
    public function updateCategory(
        Category $category,
        CategoryBlueprint $categoryBlueprintData,
        ?Category $parent = null,
        ?PreviewImage $previewImage = null
    ): Category {
        if ($parent && $this->isCircularRelation($parent, $category)) {
            throw new CircularRelationException();
        }

        $category->update([
            'title' => $categoryBlueprintData->getTitle(),
            'h1' => $categoryBlueprintData->getH1(),
            'description' => $categoryBlueprintData->getDescription(),
            'meta_title' => $categoryBlueprintData->getMetaTitle(),
            'meta_description' => $categoryBlueprintData->getMetaDescription(),
            'meta_keywords' => $categoryBlueprintData->getMetaKeywords(),
            'external_id' => $categoryBlueprintData->getExternalId(),
            'slug' => $categoryBlueprintData->getSlug()
        ]);

        if (!is_null($parent)) {
            $category->parent()->associate($parent);
        }

        $category->previewImage()->associate($previewImage);

        $category->save();
        $category->refresh();

        return $category;
    }

    public function deleteCategory(Category $category): void
    {
        /** @var Category $child */
        foreach ($category->children as $child) {
            $child->parent()->disassociate();
            $child->save();
        }

        $category->delete();
    }

    protected function isCircularRelation(Category $parent, Category $current): bool
    {
        if (null === $parent->parent) {
            return false;
        }

        if ($parent->parent->is($current)) {
            return true;
        }

        return $this->isCircularRelation($parent->parent, $current);
    }

    public function getCategoryBySlug(string $slug, bool $fail = false): ?Category
    {
        $builder = Category::query()
            ->where('slug', $slug)
            ->orWhereHas('slugAliases', function ($qb) use ($slug) {
                return $qb->where('slug', $slug);
            });

        if ($fail) {
            /** @var Category $category */
            $category = $builder->firstOrFail();
            return $category;
        }

        /** @var Category|null $category */
        $category = $builder->first();
        return $category;
    }
}
