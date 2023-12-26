<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Repository;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Support\CurrierDelivery\Address;
use App\Modules\Delivery\Support\CurrierDelivery\Fias;
use App\Modules\Users\Models\User;
use Illuminate\Support\Collection;

class CurrierDeliveryAddressRepository
{
    public function getById(int $id): CurrierDeliveryAddress
    {
        /** @var CurrierDeliveryAddress $address */
        $address = CurrierDeliveryAddress::query()->findOrFail($id);
        return $address;
    }

    public function get(User $user): Collection
    {
        return $user->currierDeliveryAddresses
            ->unique(function (CurrierDeliveryAddress $address) {
                return $address->city
                    . $address->street
                    . $address->house
                    . $address->flat;
            })
            ->flatten();
    }

    public function create(
        User $user,
        Address $address,
        Fias $fias,
        string $fullAddress
    ): CurrierDeliveryAddress {
        /** @var CurrierDeliveryAddress $currierDeliveryAddress */
        $currierDeliveryAddress = $user->currierDeliveryAddresses()->create([
            'address' => $fullAddress,
            'zip_code' => $address->zipCode,
            'region' => $address->region,
            'settlement' => $address->settlement,
            'city' => $address->city,
            'street' => $address->street,
            'house' => $address->house,
            'flat' => $address->flat,
            'block' => $address->block,
            'fias_region_id' => $fias->regionId,
            'fias_street_id' => $fias->streetId,
            'fias_house_id' => $fias->houseId
        ]);

        return $currierDeliveryAddress;
    }

    public function delete(CurrierDeliveryAddress $address): void
    {
        $address->delete();
    }
}
