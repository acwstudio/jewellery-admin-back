<?php

declare(strict_types=1);

namespace App\Modules\Stores\Repositories;

use App\Modules\Stores\Models\Store;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\UpdateStoreData;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
    public function getAll(): Collection
    {
        return Store::all();
    }

    public function getById(int $id, bool $fail = false): ?Store
    {
        /** @var Store $store */
        $store = Store::query()->where('id', $id);

        if ($fail) {
            $store->firstOrFail();
        }

        return $store->first();
    }

    public function create(CreateStoreData $createStoreData): Store
    {
        return Store::query()->create([
            'name' => $createStoreData->name,
            'description' => $createStoreData->description,
            'address' => $createStoreData->address,
            'latitude' => $createStoreData->latitude,
            'longitude' => $createStoreData->longitude,
            'phone' => $createStoreData->phone,
            'isWorkSaturday' => $createStoreData->isWorkSaturday,
            'isWorkSunday' => $createStoreData->isWorkSunday
        ]);
    }

    public function update(Store $store, UpdateStoreData $storeData): Store
    {
        $store->update([
            'name' => $storeData->name,
            'description' => $storeData->description,
            'address' => $storeData->address,
            'latitude' => $storeData->latitude,
            'longitude' => $storeData->longitude,
            'phone' => $storeData->phone,
            'isWorkSaturday' => $storeData->isWorkSaturday,
            'isWorkSunday' => $storeData->isWorkSunday
        ]);

        return $store->refresh();
    }

    public function delete(int $id): void
    {
        Store::query()->findOrFail($id)->delete();
    }

    public function syncTypesToStore(Store $store, array $idsTypes):void
    {
        $store->types()->sync($idsTypes);
    }
}
