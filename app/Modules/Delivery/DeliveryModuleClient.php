<?php

declare(strict_types=1);

namespace App\Modules\Delivery;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Delivery\Services\CurrierDeliveryAddressService;
use App\Modules\Delivery\Services\CarrierService;
use App\Modules\Delivery\Services\PvzService;
use App\Modules\Delivery\Support\Address;
use App\Modules\Delivery\Support\Location;
use App\Modules\Delivery\UseCase\CreateCurrierDelivery;
use App\Modules\Delivery\UseCase\GetDeliveryById;
use App\Modules\Delivery\UseCase\GetPvz;
use App\Modules\Delivery\UseCase\ImportPvz;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzData;
use App\Packages\DataObjects\Delivery\SavedAddressData;
use App\Packages\DataObjects\Delivery\CarrierData;
use App\Packages\DataObjects\Delivery\CreateCarrierData;
use App\Packages\DataObjects\Delivery\CreatePvzData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryData;
use App\Packages\DataObjects\Delivery\CreateCurrierDeliveryData;
use App\Packages\DataObjects\Delivery\PvzData;
use App\Packages\DataObjects\Delivery\SavedPvzData;
use App\Packages\DataObjects\Delivery\UpdateCarrierData;
use App\Packages\DataObjects\Delivery\UpdatePvzData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class DeliveryModuleClient implements DeliveryModuleClientInterface
{
    public function __construct(
        private readonly CarrierService $carrierService,
        private readonly PvzService $pvzService,
        private readonly CurrierDeliveryAddressService $addressService
    ) {
    }

    public function getPvz(GetPvzData $data): Collection
    {
        return App::call(GetPvz::class, [$data])->map(function (Pvz $pvz) {
            return PvzData::from($pvz);
        })->flatten();
    }

    public function getPvzById(int $id): PvzData
    {
        return App::call(GetDeliveryById::class, ['id' => $id, 'type' => DeliveryType::PVZ]);
    }

    public function createPvz(CreatePvzData $data): PvzData
    {
        $location = new Location(
            $data->latitude,
            $data->longitude
        );

        $address = new Address(
            $data->area,
            $data->city,
            $data->district,
            $data->street,
            $data->address
        );

        $pvz = $this->pvzService->create(
            $location,
            $address,
            $data->external_id,
            $data->carrier_id,
            $data->work_time,
            $data->price,
            new Collection()
        );

        return PvzData::from($pvz);
    }

    public function updatePvz(UpdatePvzData $data): PvzData
    {
        $location = new Location(
            $data->latitude,
            $data->longitude
        );

        $address = new Address(
            $data->area,
            $data->city,
            $data->district,
            $data->street,
            $data->address
        );

        $pvz = $this->pvzService->update(
            $data->id,
            $location,
            $address,
            $data->external_id,
            $data->work_time,
            $data->price,
            new Collection()
        );

        return PvzData::from($pvz);
    }

    public function deletePvz(int $id): void
    {
        $this->pvzService->delete($id);
    }

    public function getCarriers(): Collection
    {
        return $this->carrierService->get()->map(function (Carrier $carrier) {
            return CarrierData::from($carrier);
        });
    }

    public function getCarrierById(int $id): CarrierData
    {
        return CarrierData::from($this->carrierService->getById($id));
    }

    public function createCarrier(CreateCarrierData $data): CarrierData
    {
        $carrier = $this->carrierService->create(
            $data->name,
            $data->external_id
        );

        return CarrierData::from($carrier);
    }

    public function updateCarrier(UpdateCarrierData $data): CarrierData
    {
        $carrier = $this->carrierService->update(
            $data->id,
            $data->name,
            $data->external_id
        );

        return CarrierData::from($carrier);
    }

    public function deleteCarrier(int $id): void
    {
        $this->carrierService->delete($id);
    }

    public function createCurrierDelivery(CreateCurrierDeliveryData $data): CurrierDeliveryData
    {
        return CurrierDeliveryData::fromModel(
            App::call(CreateCurrierDelivery::class, [$data])
        );
    }

    public function getSavedAddresses(): Collection
    {
        return $this->addressService->get()->map(function (CurrierDeliveryAddress $address) {
            return new SavedAddressData(
                $address->id,
                $address->client_address
            );
        });
    }

    public function getCurrierDelivery(string $id): CurrierDeliveryData
    {
        return App::call(GetDeliveryById::class, ['id' => $id, 'type' => DeliveryType::CURRIER]);
    }

    public function getSavedPvz(): Collection
    {
        return $this->pvzService->getUserPvz()->map(function (Pvz $pvz) {
            return SavedPvzData::fromModel($pvz);
        });
    }

    public function importPvz(): void
    {
        App::call(ImportPvz::class);
    }
}
