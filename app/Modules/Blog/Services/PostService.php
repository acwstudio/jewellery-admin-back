<?php

declare(strict_types=1);

namespace App\Modules\Blog\Services;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Post;
use App\Modules\Blog\Repositories\CategoryRepository;
use App\Modules\Blog\Repositories\PostRepository;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use App\Packages\Exceptions\Blog\PostNotFoundException;
use App\Packages\ModuleClients\StorageModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PostService
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly StorageModuleClientInterface $storageModuleClient
    ) {
    }

    /**
     * @throws PostNotFoundException
     */
    public function getPostBySlug(string $slug, ?PostStatusEnum $status): Post
    {
        $post = $this->postRepository->getBySlug($slug, $status);

        if (!$post instanceof Post) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function getPostsByIds(array $ids): Collection
    {
        return $this->postRepository->getByIds($ids);
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function getPosts(
        ?string $categorySlug,
        ?PostStatusEnum $status,
        ?int $perPage = null,
        ?int $page = null,
        ?string $column = null,
        ?SortOrderEnum $orderBy = null
    ): LengthAwarePaginator {
        if (null === $categorySlug) {
            return $this->postRepository->getList(null, $status, $perPage, $page, $column, $orderBy);
        }

        $category = $this->categoryRepository->getBySlug($categorySlug);
        if (!$category instanceof Category) {
            throw new CategoryNotFoundException();
        }

        return $this->postRepository->getList($category->id, $status, $perPage, $page, $column, $orderBy);
    }

    public function createPost(
        int $categoryId,
        string $slug,
        string $title,
        ?string $content,
        PostStatusEnum $status,
        Collection $relatedPosts,
        ?int $imageId = null,
        ?int $previewId = null,
        ?Carbon $publishedAt = null,
        ?string $metaTitle = null,
        ?string $metaDescription = null,
        bool $isMain = false
    ): Post {
        $image = is_null($imageId) ? null : $this->storageModuleClient->getFile($imageId);
        $preview = is_null($previewId) ? null : $this->storageModuleClient->getFile($previewId);

        return $this->postRepository->create(
            $categoryId,
            $slug,
            $title,
            $content,
            $status,
            $relatedPosts,
            $image?->id,
            $preview?->id,
            $publishedAt,
            $metaTitle,
            $metaDescription,
            $isMain
        );
    }

    /**
     * @throws PostNotFoundException
     */
    public function updatePost(
        int $id,
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
        bool $isMain = false
    ): Post {
        $post = $this->postRepository->getById($id);

        if (!$post instanceof Post) {
            throw new PostNotFoundException();
        }

        $image = is_null($imageId) ? null : $this->storageModuleClient->getFile($imageId);
        $preview = is_null($previewId) ? null : $this->storageModuleClient->getFile($previewId);

        $this->postRepository->update(
            $post,
            $categoryId,
            $slug,
            $title,
            $content,
            $status,
            $relatedPosts,
            $image?->id,
            $preview?->id,
            $publishedAt,
            $metaTitle,
            $metaDescription,
            $isMain
        );

        return $post;
    }

    /**
     * @throws PostNotFoundException
     */
    public function deletePost(int $id): bool
    {
        $post = $this->postRepository->getById($id);

        if (!$post instanceof Post) {
            throw new PostNotFoundException();
        }

        return $this->postRepository->delete($post);
    }
}
