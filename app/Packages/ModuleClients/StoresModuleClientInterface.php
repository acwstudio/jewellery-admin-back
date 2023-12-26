<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Stores\CreateStoreData;
use App\Packages\DataObjects\Stores\StoreData;
use App\Packages\DataObjects\Stores\UpdateStoreData;
use Illuminate\Support\Collection;

interface StoresModuleClientInterface
{
    public function getAllStores(): Collection;

    public function getStoreById(int $storeId): StoreData;

    public function createStore(CreateStoreData $storeData): StoreData;

    public function updateStore(int $id, UpdateStoreData $storeData): StoreData;

    public function deleteStoreById(int $id): SuccessData;

    public function getAllStoreTypes(): Collection;
}
