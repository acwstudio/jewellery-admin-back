<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository
{
    public function getAll(): Collection
    {
        return Brand::all();
    }

    public function getById(int $id, bool $fail = false): ?Brand
    {
        /** @var Brand $brand */
        $brand = Brand::query()->where('id', $id);

        if ($fail) {
            $brand->firstOrFail();
        }

        return $brand->first();
    }

    public function create(string $name): Brand
    {
        return Brand::query()->create([
            'name' => $name
        ]);
    }

    public function update(int $id, string $name): Brand
    {
        $brand = Brand::query()->find($id);

        $brand->update(['name' => $name]);

        return $brand->refresh();
    }

    public function delete(int $id): void
    {
        Brand::query()->findOrFail($id)->delete();
    }
}
