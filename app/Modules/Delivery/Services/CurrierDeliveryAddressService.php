<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Repository\CurrierDeliveryAddressRepository;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;

class CurrierDeliveryAddressService
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly CurrierDeliveryAddressRepository $currierDeliveryAddressRepository
    ) {
    }

    public function get(): Collection
    {
        $user = $this->usersModuleClient->getUser();
        return $this->currierDeliveryAddressRepository->get($user);
    }

    public function getById(int $id): CurrierDeliveryAddress
    {
        return $this->currierDeliveryAddressRepository->getById($id);
    }
}
