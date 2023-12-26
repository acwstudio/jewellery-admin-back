<?php

declare(strict_types=1);

namespace App\Modules\Stores\Services;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Repositories\WorkTimeRepository;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\CreateWorkTimeData;
use App\Packages\DataObjects\Stores\CreateOrUpdateWorkTimeData;
use App\Packages\DataObjects\Stores\UpdateStoreData;

class WorkTimeService
{
    public function __construct(
        private readonly WorkTimeRepository $storeRepository
    ) {
    }

    public function create(CreateStoreData $storeData, Store $store): void
    {
        foreach ($storeData->work_times as $workTime) {
            $this->storeRepository->create(new CreateWorkTimeData(...$workTime), $store);
        }
    }

    public function syncWorkTimes(UpdateStoreData $storeData, Store $store): void
    {
        foreach ($storeData->work_times as $workTime) {
            $this->storeRepository->updateOrCreate(new CreateOrUpdateWorkTimeData(...$workTime), $store);
        }
    }

}
