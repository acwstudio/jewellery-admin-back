<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\Collection;

class BrandService
{
    public function __construct(
        private readonly BrandRepository $brandRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->brandRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Brand
    {
        return $this->brandRepository->getById($id, $fail);
    }

    public function create(string $name): Brand
    {
        return $this->brandRepository->create($name);
    }

    public function update(int $id, string $name): Brand
    {
        return $this->brandRepository->update($id, $name);
    }

    public function delete(int $id): void
    {
        $this->brandRepository->delete($id);
    }
}
