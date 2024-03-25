<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRelationsRepository;
use Domain\Catalog\Repositories\Product\Relationships\ProductsBlogPostsRelationshipsRepository;

final class ProductsBlogPostsStoreUpdateRelationshipsPipe
{
    public function __construct(public ProductsBlogPostsRelationshipsRepository $repository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        $id = data_get($data, 'id');
        $blogPosts = data_get($data, 'data.relationships.blogPosts');

        if ($blogPosts) {
            $dataPrices = data_set($dataPrices, 'id', $id);
            $this->repository->update($blogPosts);
        }

        return $next($data);
    }
}
