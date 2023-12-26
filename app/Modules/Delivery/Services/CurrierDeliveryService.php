<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Repository\CurrierDeliveryAddressRepository;
use App\Modules\Delivery\Repository\CurrierDeliveryRepository;
use App\Modules\Delivery\Support\CurrierDelivery\Address;
use App\Modules\Delivery\Support\CurrierDelivery\Fias;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryCostData;
use App\Packages\Exceptions\Delivery\CurrierDeliveryNotAvailableException;
use App\Packages\Facades\ShopCart;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;

class CurrierDeliveryService
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly Enterprise1CApiClientContract $enterprise1CApiClient,
        private readonly CurrierDeliveryRepository $currierDeliveryRepository,
        private readonly CurrierDeliveryAddressRepository $currierDeliveryAddressRepository
    ) {
    }

    public function get(string $id): CurrierDelivery
    {
        return $this->currierDeliveryRepository->get($id);
    }

    /**
     * @throws CurrierDeliveryNotAvailableException
     */
    public function createForFullAddress(Address $address, Fias $fias, string $fullAddress): CurrierDelivery
    {
        $user = $this->usersModuleClient->getUser();

        $currierDeliveryAddress = $this->currierDeliveryAddressRepository->create(
            $user,
            $address,
            $fias,
            $fullAddress
        );

        return $this->create($currierDeliveryAddress);
    }

    public function create(CurrierDeliveryAddress $address): CurrierDelivery
    {
        $currierDeliveryCost = $this->getCurrierDeliveryCost(
            $address->fias_street_id,
            $address->zip_code
        );

        return $this->currierDeliveryRepository->create(
            $address,
            $currierDeliveryCost->id,
            $currierDeliveryCost->cost
        );
    }

    /**
     * @throws CurrierDeliveryNotAvailableException
     */
    private function getCurrierDeliveryCost(string $fias, int $zipCode): DeliveryCostData
    {
        $response = $this->enterprise1CApiClient->deliveryGetCost($fias, $zipCode);

        if ($response->result === false || $response->data === null) {
            throw new CurrierDeliveryNotAvailableException($response->errorMessage);
        }

        if ($this->checkIsFreeDelivery()) {
            return DeliveryCostData::from([
                'id' => $response->data->id,
                'cost' => 0
            ]);
        }
        return $response->data;
    }

    private function checkIsFreeDelivery(): bool
    {
        $shopCart = ShopCart::getShopCart();
        $promocode = $this->promotionsModuleClient->getActivePromocodeExtended($shopCart->token);
        if (null === $promocode) {
            return false;
        }

        if ($promocode->is_free_delivery) {
            return true;
        }

        return false;
    }
}
