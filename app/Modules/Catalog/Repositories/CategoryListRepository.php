<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Contracts\Pipelines\CategoryQueryBuilderPipelineContract;
use App\Modules\Catalog\Models\CategoryListItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CategoryListRepository
{
    public function getCategoryList(): Collection
    {
        $query = CategoryListItem::query();

        /** @var CategoryQueryBuilderPipelineContract $pipeline */
        $pipeline = app(CategoryQueryBuilderPipelineContract::class);

        /** @var Collection $models */
        $models = $pipeline
            ->send($query)
            ->thenReturn()
            ->get();

        return $models;
    }

    public function getCategoryListItem(int $id, bool $fail = false): ?CategoryListItem
    {
        if ($fail) {
            return CategoryListItem::findOrFail($id);
        }

        return CategoryListItem::find($id);
    }

    public function getCategoryListBySlug(string $slug, bool $fail = false): ?CategoryListItem
    {
        /** @var CategoryListItem|null $model */
        $model = CategoryListItem::query()
            ->where('slug', $slug)
            ->orWhereHas('slugAliases', function ($qb) use ($slug) {
                return $qb->where('slug', $slug);
            })
            ->get()
            ->first();

        if ($fail && !$model instanceof CategoryListItem) {
            throw (new ModelNotFoundException())->setModel(CategoryListItem::class);
        }

        return $model;
    }
}
