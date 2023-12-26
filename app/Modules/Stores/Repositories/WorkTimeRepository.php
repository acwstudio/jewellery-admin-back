<?php

declare(strict_types=1);

namespace App\Modules\Stores\Repositories;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Models\StoreWorkTime;
use App\Packages\DataObjects\Stores\CreateWorkTimeData;
use App\Packages\DataObjects\Stores\CreateOrUpdateWorkTimeData;

class WorkTimeRepository
{
    public function create(CreateWorkTimeData $workTimeData, Store $store): void
    {
        $workTime = new StoreWorkTime();

        $workTime->day = $workTimeData->day;
        $workTime->start_time = $workTimeData->start_time;
        $workTime->end_time = $workTimeData->end_time;

        $workTime->store()->associate($store);
        $workTime->save();
    }

    public function updateOrCreate(CreateOrUpdateWorkTimeData $workTimeData, Store $store): void
    {
        if ($workTimeData->id != null) {
            $workTime = $this->getById($workTimeData->id, true);
        } else {
            $workTime = new StoreWorkTime();
        }

        $workTime->day = $workTimeData->day;
        $workTime->start_time = $workTimeData->start_time;
        $workTime->end_time = $workTimeData->end_time;

        $workTime->store()->associate($store);
        $workTime->save();
    }

    public function getById(int $id, bool $fail = false): ?StoreWorkTime
    {
        /** @var StoreWorkTime $store */
        $store = StoreWorkTime::query()->where('id', $id);

        if ($fail) {
            $store->firstOrFail();
        }

        return $store->first();
    }

}
