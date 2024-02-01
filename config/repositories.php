<?php


use Domain\Blog\Repositories\BlogCategory\BlogCategoryCachedRepository;
use Domain\Blog\Repositories\BlogCategory\BlogCategoryRepository;
use Domain\Blog\Repositories\BlogCategory\BlogCategoryRepositoryInterface;
use Domain\Blog\Repositories\BlogPost\BlogPostCachedRepository;
use Domain\Blog\Repositories\BlogPost\BlogPostRepository;
use Domain\Blog\Repositories\BlogPost\BlogPostRepositoryInterface;

return [
    [
        'interface'      => BlogCategoryRepositoryInterface::class,
        'implementation' => BlogCategoryRepository::class,
        'cache'          => BlogCategoryCachedRepository::class
    ],
    [
        'interface'      => BlogPostRepositoryInterface::class,
        'implementation' => BlogPostRepository::class,
        'cache'          => BlogPostCachedRepository::class
    ]
];
