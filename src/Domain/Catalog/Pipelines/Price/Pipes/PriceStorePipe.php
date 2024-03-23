<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\PriceRepository;

final class PriceStorePipe
{
    public function __construct(public PriceRepository $priceRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->priceRepository->store($data);

        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
