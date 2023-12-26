<?php

declare(strict_types=1);

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderPersonalData;

class PersonalDataRepository
{
    public function create(
        Order $order,
        CreateOrderPersonalData $personalData
    ): PersonalData {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $order->personalData()->create([
            'name' => $personalData->name,
            'surname' => $personalData->surname,
            'patronymic' => $personalData->patronymic,
            'phone' => $personalData->phone,
            'email' => $personalData->email,
        ]);
    }
}
