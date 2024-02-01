<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogCategory;

use Domain\Blog\Models\BlogCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class BlogCategoryRepository implements BlogCategoryRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(BlogCategory::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('blog_categories'))
            ->allowedIncludes(['blogPosts','parent','children'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('parent_id'),
                'name','active'
            ])
            ->allowedSorts(['name','id','parent_id'])
            ->simplePaginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|BlogCategory
    {
        return BlogCategory::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|BlogCategory
    {
        return QueryBuilder::for(BlogCategory::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('blog_categories'))
            ->allowedIncludes(['blogPosts','parent','children'])
            ->firstOrFail();
    }

    public function update(array $data): Model|BlogCategory
    {
        $model = BlogCategory::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        BlogCategory::findOrFail($id)->delete();
    }
}
