<?php

namespace App\Modules\Stores\Services;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Repositories\SubwayRepository;

class SubwayService
{
    public function __construct(
        private readonly SubwayRepository $subwayRepository
    )
    {
    }

    public function saveSubwaysToStore(Store $store, array $addressData): void
    {
        foreach ($addressData[0]['metro'] as $metro) {
            $subway = $this->subwayRepository->getByStation($metro['name']);
            if (!$subway) {
                continue;
            }
            $store->subways()->attach($subway->id, ['distance' => floatval($metro['distance'])]);
        }
    }

    public function restoreSubwaysStore(Store $store, array $addressData): void
    {
        $store->subways()->detach();
        $this->saveSubwaysToStore($store, $addressData);
    }
}
