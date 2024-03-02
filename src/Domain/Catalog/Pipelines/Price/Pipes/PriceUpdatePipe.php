<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\PriceRepository;

final class PriceUpdatePipe
{
    public function __construct(public PriceRepository $priceRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->priceRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
