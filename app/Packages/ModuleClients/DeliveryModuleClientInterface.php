<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Delivery\CarrierData;
use App\Packages\DataObjects\Delivery\CreateCarrierData;
use App\Packages\DataObjects\Delivery\CreatePvzData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryData;
use App\Packages\DataObjects\Delivery\CreateCurrierDeliveryData;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzData;
use App\Packages\DataObjects\Delivery\PvzData;
use App\Packages\DataObjects\Delivery\UpdateCarrierData;
use App\Packages\DataObjects\Delivery\UpdatePvzData;
use Illuminate\Support\Collection;

interface DeliveryModuleClientInterface
{
    public function getPvz(GetPvzData $data): Collection;

    public function getPvzById(int $id): PvzData;

    public function createPvz(CreatePvzData $data): PvzData;

    public function updatePvz(UpdatePvzData $data): PvzData;

    public function deletePvz(int $id): void;

    public function getCarriers(): Collection;

    public function getCarrierById(int $id): CarrierData;

    public function createCarrier(CreateCarrierData $data): CarrierData;

    public function updateCarrier(UpdateCarrierData $data): CarrierData;

    public function deleteCarrier(int $id): void;

    public function getCurrierDelivery(string $id): CurrierDeliveryData;

    public function createCurrierDelivery(CreateCurrierDeliveryData $data): CurrierDeliveryData;

    public function getSavedAddresses(): Collection;

    public function getSavedPvz(): Collection;

    public function importPvz(): void;
}
