<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Repository;

use App\Modules\Delivery\Models\Carrier;
use Illuminate\Database\Eloquent\Collection;

class CarrierRepository
{
    public function get(): Collection
    {
        return Carrier::all();
    }

    public function getByIdOrFail(int $id): Carrier
    {
        /** @var Carrier $carrier */
        $carrier = Carrier::query()->findOrFail($id);
        return $carrier;
    }

    public function getByExternalIdOrFail(string $externalId): Carrier
    {
        /** @var Carrier $carrier */
        $carrier = Carrier::query()->where('external_id', $externalId)->firstOrFail();
        return $carrier;
    }

    public function create(string $name, string $externalId): Carrier
    {
        /** @var Carrier $carrier */
        $carrier = Carrier::query()->create([
            'name' => $name,
            'external_id' => $externalId,
        ]);

        return $carrier;
    }

    public function upsert(string $name, string $externalId): Carrier
    {
        /** @var Carrier $carrier */
        $carrier = Carrier::query()->updateOrCreate([
            'name' => $name,
            'external_id' => $externalId,
        ]);

        return $carrier;
    }


    public function update(
        Carrier $carrier,
        string $name,
        string $externalId
    ): Carrier {
        $carrier->update([
            'name' => $name,
            'external_id' => $externalId,
        ]);

        return $carrier->refresh();
    }

    public function delete(Carrier $carrier): void
    {
        $carrier->delete();
    }
}
