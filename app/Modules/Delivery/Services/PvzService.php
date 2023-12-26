<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\Metro;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Repository\CarrierRepository;
use App\Modules\Delivery\Repository\PvzRepository;
use App\Modules\Delivery\Support\Address;
use App\Modules\Delivery\Support\Location;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Money\Money;

class PvzService
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly PvzRepository $pvzRepository,
        private readonly CarrierRepository $carrierRepository
    ) {
    }

    public function get(string $city): Collection
    {
        return $this->pvzRepository->get($city);
    }

    public function getUserPvz(): Collection
    {
        $user = $this->usersModuleClient->getUser();
        return $this->pvzRepository->getUserPvz($user);
    }

    public function getById(int $id, bool $fail = true, bool $withTrashed = false): ?Pvz
    {
        return $this->pvzRepository->getByIdOrFail($id, $fail, $withTrashed);
    }

    public function getByExternalId(string $externalId): ?Pvz
    {
        return $this->pvzRepository->getByExternalId($externalId);
    }

    /**
     * @param Collection<Metro> $metro
     */
    public function create(
        Location $location,
        Address $address,
        string $externalId,
        Carrier|int $carrier,
        string $workTime,
        Money $price,
        Collection $metro,
    ): Pvz {
        if (is_int($carrier)) {
            $carrier = $this->carrierRepository->getByIdOrFail($carrier);
        }

        return $this->pvzRepository->create(
            $location,
            $address,
            $externalId,
            $carrier,
            $workTime,
            $price,
            $metro
        );
    }

    public function update(
        Pvz|int $pvz,
        Location $location,
        Address $address,
        string $externalId,
        string $workTime,
        Money $price,
        Collection $metro
    ): Pvz {
        if (is_int($pvz)) {
            $pvz = $this->pvzRepository->getByIdOrFail($pvz);
        }

        return $this->pvzRepository->update(
            $pvz,
            $location,
            $address,
            $externalId,
            $workTime,
            $price,
            $metro
        );
    }

    public function delete(Pvz|int $pvz): void
    {
        if (is_int($pvz)) {
            $pvz = $this->pvzRepository->getByIdOrFail($pvz);
        }

        $this->pvzRepository->delete($pvz);
    }
}
