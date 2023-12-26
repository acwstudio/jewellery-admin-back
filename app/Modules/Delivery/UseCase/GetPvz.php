<?php

declare(strict_types=1);

namespace App\Modules\Delivery\UseCase;

use App\Modules\Delivery\Services\PvzCacheService;
use App\Modules\Delivery\Services\PvzService;
use App\Modules\Delivery\Support\Pvz\Filter\Passable;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzData;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzFilterData;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class GetPvz
{
    public function __construct(
        private readonly PvzService $pvzService,
        private readonly PvzCacheService $pvzCacheService,
        private readonly array $filterPipes
    ) {
    }

    public function __invoke(GetPvzData $data): Collection
    {
        $city = $this->getCity($data);

        $pvz = $this->pvzCacheService->get($city, function () use ($city) {
            return $this->pvzService->get($city);
        });

        if ($data->filter === null) {
            return $pvz;
        }

        return $this->filterPvz($pvz, $data->filter);
    }

    private function getCity(GetPvzData $data): string
    {
        if ($data->filter === null || $data->filter->address === null) {
            return config('delivery.default_city');
        }

        return $data->filter->address->city;
    }

    private function filterPvz(Collection $pvz, GetPvzFilterData $data): Collection
    {
        $pipeline = App::make(Pipeline::class);
        $passable = new Passable($pvz, $data);

        return $pipeline->send($passable)->through($this->filterPipes)->then(function (Passable $passable) {
            return $passable->pvz;
        });
    }
}
