<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\PriceRepository;

final class PriceDestroyPipe
{
    public function __construct(public PriceRepository $priceRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->priceRepository->destroy($id);

        return $next($id);
    }
}
