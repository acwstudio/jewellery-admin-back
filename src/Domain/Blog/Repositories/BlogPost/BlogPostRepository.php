<?php

declare(strict_types=1);

namespace Domain\Blog\Repositories\BlogPost;

use Domain\Blog\Models\BlogPost;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class BlogPostRepository implements BlogPostRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(BlogPost::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('blog_posts'))
            ->allowedIncludes(['blogCategory'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
            'active','title','content'
            ])
            ->allowedSorts(['title','id'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|BlogPost
    {
        return BlogPost::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|BlogPost
    {
        return QueryBuilder::for(BlogPost::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('blog_posts'))
            ->allowedIncludes(['blogCategory'])
            ->firstOrFail();
    }

    public function update(array $data): Model|BlogPost
    {
        $model = BlogPost::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        BlogPost::findOrFail($id)->delete();
    }
}
