<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Repository;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\Metro;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Support\Address;
use App\Modules\Delivery\Support\Location;
use App\Modules\Users\Models\User;
use Illuminate\Support\Collection;
use Money\Money;

class PvzRepository
{
    public function get(string $city): Collection
    {
        return Pvz::query()
            ->where('city', 'ilike', $city)
            ->get();
    }

    public function getUserPvz(User $user): Collection
    {
        return $user->pvz;
    }

    public function getByIdOrFail(int $id, bool $fail = true, bool $withTrashed = false): ?Pvz
    {
        $query = Pvz::query();
        if ($withTrashed) {
            $query = Pvz::withTrashed();
        }

        if ($fail) {
            /** @var Pvz $pvz */
            $pvz = $query->findOrFail($id);
            return $pvz;
        }

        /** @var Pvz|null $pvz */
        $pvz = $query->find($id);
        return $pvz;
    }

    public function getByExternalId(string $externalId): ?Pvz
    {
        /** @var Pvz|null $pvz */
        $pvz = Pvz::query()->where('external_id', $externalId)->first();
        return $pvz;
    }

    public function create(
        Location $location,
        Address $address,
        string $externalId,
        Carrier $carrier,
        string $workTime,
        Money $price,
        Collection $metro
    ): Pvz {
        /** @var Pvz $pvz */
        $pvz = $carrier->pvz()->create([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'external_id' => $externalId,
            'area' => $address->area,
            'city' => $address->city,
            'street' => $address->street,
            'district' => $address->district,
            'address' => $address->address,
            'work_time' => $workTime,
            'price' => $price,
        ]);

        $pvz->metro()->sync($metro->pluck('id'));

        return $pvz;
    }

    public function update(
        Pvz $pvz,
        Location $location,
        Address $address,
        string $externalId,
        string $workTime,
        Money $price,
        Collection $metro
    ): Pvz {
        $pvz->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'external_id' => $externalId,
            'area' => $address->area,
            'city' => $address->city,
            'street' => $address->street,
            'district' => $address->district,
            'address' => $address->address,
            'work_time' => $workTime,
            'price' => $price,
        ]);

        $pvz->metro()->sync($metro->pluck('id'));

        return $pvz->refresh();
    }

    public function delete(Pvz $pvz): void
    {
        $pvz->delete();
    }
}
