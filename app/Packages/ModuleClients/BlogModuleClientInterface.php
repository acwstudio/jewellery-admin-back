<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

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

interface BlogModuleClientInterface
{
    public function getCategory(string $slug): CategoryData;

    public function getCategories(GetCategoryListData $data): CategoryListData;

    public function createCategory(CreateCategoryData $data): CategoryData;

    public function updateCategory(UpdateCategoryData $data): CategoryData;

    public function deleteCategory(int $categoryId): bool;

    public function getPost(string $slug): PostData;

    public function getPosts(GetPostListData $data): PostListData;

    public function createPost(CreatePostData $data): PostData;

    public function updatePost(UpdatePostData $data): PostData;

    public function deletePost(int $postId): bool;
}
