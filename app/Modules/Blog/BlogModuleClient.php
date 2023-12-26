<?php

declare(strict_types=1);

namespace App\Modules\Blog;

use App\Modules\Blog\Services\CategoryService;
use App\Modules\Blog\Services\PostService;
use App\Packages\DataObjects\Blog\Category\CategoryData;
use App\Packages\DataObjects\Blog\Category\CategoryListData;
use App\Packages\DataObjects\Blog\Category\CreateCategoryData;
use App\Packages\DataObjects\Blog\Category\GetCategoryListData;
use App\Packages\DataObjects\Blog\Category\UpdateCategoryData;
use App\Packages\DataObjects\Blog\Post\CreatePostData;
use App\Packages\DataObjects\Blog\Post\GetPostListData;
use App\Packages\DataObjects\Blog\Post\PostData;
use App\Packages\DataObjects\Blog\Post\PostListData;
use App\Packages\DataObjects\Blog\Post\UpdatePostData;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\ModuleClients\BlogModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class BlogModuleClient implements BlogModuleClientInterface
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly PostService $postService
    ) {
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function getCategory(string $slug): CategoryData
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        return CategoryData::fromModel($category);
    }

    public function getCategories(GetCategoryListData $data): CategoryListData
    {
        $pagination = $this->categoryService->getCategories(
            $data->pagination?->per_page,
            $data->pagination?->page,
            $data->sort?->sort_by,
            $data->sort?->sort_order
        );

        return CategoryListData::fromPaginator($pagination);
    }

    public function createCategory(CreateCategoryData $data): CategoryData
    {
        /** TODO Добавить проверку на право создание категории */
        $category = $this->categoryService->createCategory(
            Str::slug($data->slug),
            $data->name,
            $data->position,
            $data->meta_description
        );

        return CategoryData::fromModel($category);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function updateCategory(UpdateCategoryData $data): CategoryData
    {
        /** TODO Добавить проверку на право обновление категории */
        $category = $this->categoryService->updateCategory(
            $data->id,
            Str::slug($data->slug),
            $data->name,
            $data->position,
            $data->meta_description
        );

        return CategoryData::fromModel($category);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function deleteCategory(int $categoryId): bool
    {
        /** TODO Добавить проверку на право удаление категории */
        return $this->categoryService->deleteCategory($categoryId);
    }

    public function getPost(string $slug): PostData
    {
        /** TODO Добавить проверку на админа для получения другого статуса поста! */
        $status = PostStatusEnum::PUBLISHED;

        $post = $this->postService->getPostBySlug($slug, $status);

        return PostData::fromModel($post);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function getPosts(GetPostListData $data): PostListData
    {
        /** TODO Добавить проверку на админа для получения всех статусов постов */
        $status = PostStatusEnum::PUBLISHED;

        $pagination = $this->postService->getPosts(
            $data->category,
            $status,
            $data->pagination?->per_page,
            $data->pagination?->page,
            $data->sort?->sort_by,
            $data->sort?->sort_order
        );

        return PostListData::fromPaginator($pagination);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function createPost(CreatePostData $data): PostData
    {
        /** TODO Добавить проверку на право создание поста */
        $category = $this->categoryService->getCategory($data->category_id);
        $relatedPosts = $this->getRelatedPosts($data->related_posts);

        $post = $this->postService->createPost(
            $category->id,
            Str::slug($data->slug),
            $data->title,
            $data->content,
            $data->status,
            $relatedPosts,
            $data->image_id,
            $data->preview_id,
            $data->published_at,
            $data->meta_title,
            $data->meta_description
        );

        return PostData::fromModel($post);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\PostNotFoundException
     * @throws \App\Packages\Exceptions\Blog\CategoryNotFoundException
     */
    public function updatePost(UpdatePostData $data): PostData
    {
        /** TODO Добавить проверку на право редактирование поста */
        $category = $this->categoryService->getCategory($data->category_id);
        $relatedPosts = $this->getRelatedPosts($data->related_posts);

        $post = $this->postService->updatePost(
            $data->id,
            $category->id,
            Str::slug($data->slug),
            $data->title,
            $data->content,
            $data->status,
            $relatedPosts,
            $data->image_id,
            $data->preview_id,
            $data->published_at,
            $data->meta_title,
            $data->meta_description
        );

        return PostData::fromModel($post);
    }

    /**
     * @throws \App\Packages\Exceptions\Blog\PostNotFoundException
     */
    public function deletePost(int $postId): bool
    {
        /** TODO Добавить проверку на право удаление поста */
        return $this->postService->deletePost($postId);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    private function getRelatedPosts(array $ids): Collection
    {
        return $this->postService->getPostsByIds($ids);
    }
}
