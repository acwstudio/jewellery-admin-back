<?php


use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryCachedRepository;
use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRepository;
use Domain\Blog\Repositories\BlogCategoryRepository\BlogCategoryRepositoryInterface;

return [
    [
        'interface'      => BlogCategoryRepositoryInterface::class,
        'implementation' => BlogCategoryRepository::class,
        'cache'          => BlogCategoryCachedRepository::class
    ],
//    [
//        'interface'      => BlogPostRepositoryInterface::class,
//        'implementation' => BlogPostRepository::class,
//        'cache'          => BlogPostCachedRepository::class
//    ]
];