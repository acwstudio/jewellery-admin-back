<?php

declare(strict_types=1);

namespace App\Modules\Blog\Repositories;

use App\Modules\Blog\Models\Category;
use App\Packages\Enums\SortOrderEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository
{
    private const POSITION = 10;

    public function getById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function getBySlug(string $slug): ?Category
    {
        /** @var Category|null $category */
        $category = Category::query()->where('slug', '=', $slug)->first();

        return $category;
    }

    public function getList(?int $perPage, ?int $currentPage, ?string $column, ?SortOrderEnum $orderBy): LengthAwarePaginator
    {
        $builder = Category::query();
        $builder = self::sort($builder, $column, $orderBy);

        return $builder->paginate($perPage, ['*'], 'page', $currentPage);
    }

    public function create(
        string  $slug,
        string  $name,
        ?int    $position = null,
        ?string $metaDescription = null
    ): Category {
        $category = new Category([
            'slug' => $slug,
            'name' => $name,
            'position' => $position ?? self::POSITION,
            'meta_description' => $metaDescription
        ]);

        $category->save();

        return $category;
    }

    public function update(
        Category $category,
        string   $slug,
        string   $name,
        ?int     $position = null,
        ?string  $metaDescription = null
    ): bool {
        return $category->update([
            'slug' => $slug,
            'name' => $name,
            'position' => $position ?? self::POSITION,
            'meta_description' => $metaDescription
        ]);
    }

    public function delete(Category $category): bool
    {
        return $category->delete() ?? false;
    }

    private static function sort(Builder $builder, ?string $column, ?SortOrderEnum $orderBy): Builder
    {
        if (empty($column)) {
            return $builder->orderByDesc('id');
        }

        if (!$orderBy instanceof SortOrderEnum) {
            $orderBy = SortOrderEnum::ASC;
        }

        return $builder->orderBy($column, $orderBy->value);
    }
}
