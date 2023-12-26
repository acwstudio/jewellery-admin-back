<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Repositories\DeliveryRepository;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderDeliveryData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\Exceptions\Delivery\CurrierDeliveryNotAvailableException;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;

class DeliveryService
{
    public function __construct(
        private readonly DeliveryRepository $deliveryRepository,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
    ) {
    }

    /**
     * @throws CurrierDeliveryNotAvailableException
     */
    public function create(
        Order $order,
        CreateOrderDeliveryData $deliveryData
    ): Delivery {
        if ($deliveryData->deliveryType === DeliveryType::CURRIER && $deliveryData->currierDeliveryId) {
            return $this->createCurrierDelivery(
                $order,
                $deliveryData->currierDeliveryId
            );
        }

        if ($deliveryData->deliveryType === DeliveryType::PVZ && $deliveryData->pvzId) {
            return $this->createPvzDelivery(
                $order,
                $deliveryData->pvzId
            );
        }

        throw new CurrierDeliveryNotAvailableException();
    }

    private function createCurrierDelivery(Order $order, string $currierDeliveryId): Delivery
    {
        $delivery = $this->deliveryModuleClient->getCurrierDelivery($currierDeliveryId);

        return $this->deliveryRepository->createCurrierDelivery(
            $order,
            $delivery->price,
            $currierDeliveryId
        );
    }

    private function createPvzDelivery(Order $order, int $pvzId): Delivery
    {
        $pvz = $this->deliveryModuleClient->getPvzById($pvzId);

        return $this->deliveryRepository->createPvzDelivery(
            $order,
            $pvz->price,
            $pvzId
        );
    }
}
