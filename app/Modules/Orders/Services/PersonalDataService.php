<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Modules\Orders\Repositories\PersonalDataRepository;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderPersonalData;

class PersonalDataService
{
    public function __construct(
        private readonly PersonalDataRepository $orderPersonalDataRepository
    ) {
    }

    public function create(Order $order, CreateOrderPersonalData $personalData): PersonalData
    {
        return $this->orderPersonalDataRepository->create($order, $personalData);
    }
}
