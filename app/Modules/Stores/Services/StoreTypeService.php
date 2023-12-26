<?php

declare(strict_types=1);

namespace App\Modules\Stores\Services;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Repositories\StoreTypeRepository;
use App\Packages\DataObjects\Stores\CreateStoreData;
use Illuminate\Database\Eloquent\Collection;

class StoreTypeService
{
    public function __construct(
        private readonly StoreTypeRepository $storeTypeRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->storeTypeRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Store
    {
        return $this->storeTypeRepository->getById($id, $fail);
    }

    public function create(CreateStoreData $storeData): Store
    {
        return $this->storeTypeRepository->create($storeData);
    }

    public function update(int $id, string $name): Store
    {
        return $this->storeTypeRepository->update($id, $name);
    }

    public function delete(int $id): void
    {
        $this->storeTypeRepository->delete($id);
    }
}
