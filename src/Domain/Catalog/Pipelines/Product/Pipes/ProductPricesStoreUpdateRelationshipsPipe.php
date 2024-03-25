<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\Relationships\ProductPricesRelationshipsRepository;

final class ProductPricesStoreUpdateRelationshipsPipe
{
    public function __construct(ProductPricesRelationshipsRepository $repository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        // HasManyThrough updating can't be made with RESTful API. It needs something like GraphQL

        return $next($id);
    }
}
