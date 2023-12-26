<?php

declare(strict_types=1);

namespace App\Modules\Delivery\UseCase;

use App\Modules\Delivery\Models\Metro;
use App\Modules\Delivery\Services\CurrierDeliveryService;
use App\Modules\Delivery\Services\PvzService;
use App\Packages\DataObjects\Delivery\CarrierData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryAddressData;
use App\Packages\DataObjects\Delivery\CurrierDeliveryData;
use App\Packages\DataObjects\Delivery\MetroData;
use App\Packages\DataObjects\Delivery\PvzData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\Facades\ShopCart;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Money\Money;

class GetDeliveryById
{
    public function __construct(
        private readonly PvzService $pvzService,
        private readonly CurrierDeliveryService $currierDeliveryService,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
    ) {
    }

    public function __invoke(int|string $id, DeliveryType $type): PvzData|CurrierDeliveryData
    {
        return match ($type) {
            DeliveryType::PVZ => $this->getPvzData($id),
            DeliveryType::CURRIER => $this->getCurrierDeliveryData($id),
        };
    }

    private function getPvzData(int $id): PvzData
    {
        $pvz = $this->pvzService->getById($id, true, true);

        if ($this->checkIsFreeDelivery()) {
            return new PvzData(
                $pvz->id,
                $pvz->external_id,
                $pvz->latitude,
                $pvz->longitude,
                $pvz->work_time,
                $pvz->area,
                $pvz->city,
                $pvz->district,
                $pvz->street,
                CarrierData::from($pvz->carrier),
                Money::RUB(0),
                $pvz->address,
                $pvz->metro->map(function (Metro $metro) {
                    return MetroData::from($metro);
                }),
            );
        }

        return PvzData::fromModel($pvz);
    }

    private function getCurrierDeliveryData(string $id): CurrierDeliveryData
    {
        $currierDelivery = $this->currierDeliveryService->get($id);

        if ($this->checkIsFreeDelivery()) {
            return new CurrierDeliveryData(
                $currierDelivery->id,
                $currierDelivery->carrier_id,
                Money::RUB(0),
                $currierDelivery->address->address,
                CurrierDeliveryAddressData::from($currierDelivery->address)
            );
        }

        return CurrierDeliveryData::fromModel($currierDelivery);
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
