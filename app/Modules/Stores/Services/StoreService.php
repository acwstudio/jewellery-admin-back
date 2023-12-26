<?php

declare(strict_types=1);

namespace App\Modules\Stores\Services;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Repositories\StoreRepository;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\UpdateStoreData;
use Illuminate\Database\Eloquent\Collection;

class StoreService
{
    public function __construct(
        private readonly StoreRepository $storeRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->storeRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Store
    {
        return $this->storeRepository->getById($id, $fail);
    }

    public function create(CreateStoreData $storeData): Store
    {
        [$storeData->isWorkSaturday, $storeData->isWorkSunday] = $this->getWorkSchedule($storeData);

        $store = $this->storeRepository->create($storeData);

        $this->syncTypesToStore($store, $storeData->types);

        return $store->refresh();
    }

    public function update(Store $store, UpdateStoreData $storeData): Store
    {
        [$storeData->isWorkSaturday, $storeData->isWorkSunday] = $this->getWorkSchedule($storeData);

        $this->syncTypesToStore($store, $storeData->types);

        return $this->storeRepository->update($store, $storeData);
    }

    public function delete(int $id): void
    {
        $this->storeRepository->delete($id);
    }

    public function syncTypesToStore(Store $store, array $idsTypes):void
    {
        $this->storeRepository->syncTypesToStore($store, $idsTypes);
    }

    private function getWorkSchedule(CreateStoreData|UpdateStoreData $storeData)
    {
        $isWorkSaturday = array_reduce($storeData->work_times, function($carry, $workTime) {
            return $carry || ($workTime['day'] === StoreWorkDayEnum::SATURDAY);
        }, false);
        $isWorkSunday = array_reduce($storeData->work_times, function($carry, $workTime) {
            return $carry || ($workTime['day'] === StoreWorkDayEnum::SUNDAY);
        }, false);

        return [$isWorkSaturday, $isWorkSunday];
    }
}
