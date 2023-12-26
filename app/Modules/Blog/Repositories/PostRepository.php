<?php

declare(strict_types=1);

namespace App\Modules\Blog\Repositories;

use App\Modules\Blog\Models\Post;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\Enums\SortOrderEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class PostRepository
{
    public function getById(int $id): ?Post
    {
        return Post::find($id);
    }

    public function getByIds(array $ids): Collection
    {
        return Post::query()->findMany($ids);
    }

    public function getBySlug(string $slug, ?PostStatusEnum $status = null): ?Post
    {
        $builder = Post::query();

        if (null !== $status) {
            $builder->where('status', '=', $status);
        }

        /** @var Post $post */
        $post = $builder->where('slug', '=', $slug)->first();

        return $post;
    }

    public function getList(
        ?int $categoryId,
        ?PostStatusEnum $status,
        ?int $perPage = null,
        ?int $page = null,
        ?string $column = null,
        ?SortOrderEnum $orderBy = null
    ): LengthAwarePaginator {
        $builder = Post::query();

        if (null !== $categoryId) {
            $builder->where('category_id', '=', $categoryId);
        }

        if (null !== $status) {
            $builder->where('status', '=', $status);
        }

        $builder = $this->sort($builder, $column, $orderBy);

        return $builder->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(
        int $categoryId,
        string $slug,
        string $title,
        string $content,
        PostStatusEnum $status,
        Collection $relatedPosts,
        ?int $imageId = null,
        ?int $previewId = null,
        ?Carbon $publishedAt = null,
        ?string $metaTitle = null,
        ?string $metaDescription = null,
        ?bool $isMain = false
    ): Post {
        if (null === $publishedAt && PostStatusEnum::PUBLISHED === $status) {
            $publishedAt = Carbon::now();
        }

        $post = new Post([
            'category_id' => $categoryId,
            'slug' => $slug,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'image_id' => $imageId,
            'preview_id' => $previewId,
            'published_at' => $publishedAt,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'is_main' => $isMain
        ]);

        $post->save();
        $post->relatedPosts()->attach($relatedPosts);

        return $post;
    }

    public function update(
        Post $post,
        int $category_id,
        string $slug,
        string $title,
        string $content,
        PostStatusEnum $status,
        Collection $relatedPosts,
        ?int $imageId = null,
        ?int $previewId = null,
        ?\DateTimeInterface $publishedAt = null,
        ?string $metaTitle = null,
        ?string $metaDescription = null,
        ?bool $isMain = false
    ): bool {
        if (null === $publishedAt && PostStatusEnum::PUBLISHED === $status) {
            $publishedAt = Carbon::now();
        }

        $post->relatedPosts()->sync($relatedPosts);

        return $post->update([
            'category_id' => $category_id,
            'slug' => $slug,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'image_id' => $imageId,
            'preview_id' => $previewId,
            'published_at' => $publishedAt,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'is_main' => $isMain
        ]);
    }

    public function delete(Post $post): bool
    {
        return $post->delete() ?? false;
    }

    private function sort(Builder $builder, ?string $column, ?SortOrderEnum $orderBy): Builder
    {
        if (empty($column)) {
            return $builder->orderByDesc('published_at');
        }

        if (!$orderBy instanceof SortOrderEnum) {
            $orderBy = SortOrderEnum::ASC;
        }

        return $builder->orderBy($column, $orderBy->value);
    }
}
