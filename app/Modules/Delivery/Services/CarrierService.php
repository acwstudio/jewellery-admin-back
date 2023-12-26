<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Repository\CarrierRepository;
use Illuminate\Support\Collection;

class CarrierService
{
    public function __construct(
        private readonly CarrierRepository $carrierRepository,
    ) {
    }

    public function get(): Collection
    {
        return $this->carrierRepository->get();
    }

    public function getById(int $id): Carrier
    {
        return $this->carrierRepository->getByIdOrFail($id);
    }

    public function getByExternalId(string $externalId): Carrier
    {
        return $this->carrierRepository->getByExternalIdOrFail($externalId);
    }

    public function create(
        string $name,
        string $externalId,
    ): Carrier {
        return $this->carrierRepository->create(
            $name,
            $externalId
        );
    }

    public function upsert(
        string $name,
        string $externalId,
    ): Carrier {
        return $this->carrierRepository->upsert(
            $name,
            $externalId
        );
    }

    public function update(
        Carrier|int $carrier,
        string $name,
        string $externalId
    ): Carrier {
        if (is_int($carrier)) {
            $carrier = $this->carrierRepository->getByIdOrFail($carrier);
        }

        return $this->carrierRepository->update(
            $carrier,
            $name,
            $externalId
        );
    }

    public function delete(Carrier|int $carrier): void
    {
        if (is_int($carrier)) {
            $carrier = $this->carrierRepository->getByIdOrFail($carrier);
        }

        $this->carrierRepository->delete($carrier);
    }
}
