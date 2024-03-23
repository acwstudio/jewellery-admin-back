<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\Relationships\PricesProductRelationshipsRepository;

final class PricesProductStoreUpdateRelationshipsPipe
{
    public function __construct(protected PricesProductRelationshipsRepository $repository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        // HasOneThrough updating doesn't make sense. You can do something another

        return $next($data);
    }
}
