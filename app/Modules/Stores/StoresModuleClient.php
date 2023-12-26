<?php

declare(strict_types=1);

namespace App\Modules\Stores;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Models\StoreType;
use App\Modules\Stores\Services\StoreService;
use App\Modules\Stores\Services\StoreTypeService;
use App\Modules\Stores\Services\SubwayService;
use App\Modules\Stores\Services\WorkTimeService;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\StoreData;
use App\Packages\DataObjects\Stores\StoreTypeData;
use App\Packages\DataObjects\Stores\UpdateStoreData;
use App\Packages\ModuleClients\StoresModuleClientInterface;
use Illuminate\Support\Collection;
use MoveMoveIo\DaData\Facades\DaDataAddress;

final class StoresModuleClient implements StoresModuleClientInterface
{
    public function __construct(
        private readonly StoreService $storeService,
        private readonly StoreTypeService $storeTypeService,
        private readonly SubwayService $subwayService,
        private readonly WorkTimeService $workTimeService
    ) {
    }

    public function getAllStores(): Collection
    {
        /** @var Collection<Store> $stores */
        $stores = $this->storeService->getAll();

        return $stores->map(function (Store $store) {
            return StoreData::fromModel($store);
        });
    }

    public function getStoreById(int $storeId): StoreData
    {
        return StoreData::fromModel($this->storeService->getById($storeId, true));
    }

    public function createStore(CreateStoreData $storeData): StoreData
    {
        $store = $this->storeService->create($storeData);

        /** @var array $addressData */
        /** @phpstan-ignore-next-line */
        $addressData = DaDataAddress::standardization($storeData->address);
        if (!empty($addressData[0]['metro'])) {
            $this->subwayService->saveSubwaysToStore($store, $addressData);
        }
        $this->workTimeService->create($storeData, $store);
        return StoreData::fromModel($store);
    }

    public function updateStore(int $id, UpdateStoreData $storeData): StoreData
    {
        $store = $this->storeService->getById($id, true);
        $isUpdateSubways = $store->address != $storeData->address;

        $store = $this->storeService->update($store, $storeData);
        if ($isUpdateSubways) {
             /** @var array $addressData */
            /** @phpstan-ignore-next-line */
             $addressData = DaDataAddress::standardization($storeData->address);
            if (!empty($addressData[0]['metro'])) {
                $this->subwayService->restoreSubwaysStore($store, $addressData);
            }
        }

        $this->workTimeService->syncWorkTimes($storeData, $store);

        return StoreData::fromModel($store);
    }

    public function deleteStoreById(int $id): SuccessData
    {
        $this->storeService->delete($id);
        return new SuccessData();
    }

    public function getAllStoreTypes(): Collection
    {
        /** @var Collection<StoreType> $storeTypes */
        $storeTypes = $this->storeTypeService->getAll();

        return $storeTypes->map(function (StoreType $storeType) {
            return StoreTypeData::fromModel($storeType);
        });
    }
}
