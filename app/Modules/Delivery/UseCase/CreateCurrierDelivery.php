<?php

declare(strict_types=1);

namespace App\Modules\Delivery\UseCase;

use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Services\CurrierDeliveryAddressService;
use App\Modules\Delivery\Services\CurrierDeliveryService;
use App\Modules\Delivery\Support\CurrierDelivery\Address;
use App\Modules\Delivery\Support\CurrierDelivery\Fias;
use App\Packages\DataObjects\Delivery\CreateCurrierDeliveryData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryAddressData;
use App\Packages\Exceptions\Delivery\CurrierDeliveryNotAvailableException;

class CreateCurrierDelivery
{
    public function __construct(
        private readonly CurrierDeliveryService $currierDeliveryService,
        private readonly CurrierDeliveryAddressService $currierDeliveryAddressService
    ) {
    }

    /**
     * @throws CurrierDeliveryNotAvailableException
     */
    public function __invoke(CreateCurrierDeliveryData $data): CurrierDelivery
    {
        if ($data->deliveryAddressId !== null) {
            return $this->createById($data->deliveryAddressId);
        }

        if ($data->deliveryAddress !== null) {
            return $this->createForFullAddress($data->deliveryAddress);
        }

        throw new CurrierDeliveryNotAvailableException();
    }

    private function createById(int $id): CurrierDelivery
    {
        $address = $this->currierDeliveryAddressService->getById($id);
        return $this->currierDeliveryService->create($address);
    }

    private function createForFullAddress(CurrierDeliveryAddressData $data): CurrierDelivery
    {
        $address = new Address(
            $data->zipCode,
            $data->region,
            $data->city,
            $data->street,
            $data->house,
            $data->settlement,
            $data->flat,
            $data->block
        );

        $fias = new Fias(
            $data->regionFiasId,
            $data->streetFiasId,
            $data->houseFiasId
        );

        return $this->currierDeliveryService->createForFullAddress($address, $fias, $data->fullAddress);
    }
}
