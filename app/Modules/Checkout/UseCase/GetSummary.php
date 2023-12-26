<?php

declare(strict_types=1);

namespace App\Modules\Checkout\UseCase;

use App\Modules\Checkout\Services\CheckoutService;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryDeliveryData;
use App\Packages\DataObjects\Checkout\Summary\SummaryData;
use App\Packages\Exceptions\Checkout\EmptyShopCardException;
use App\Packages\Exceptions\Checkout\InsufficientQtyOfGoodsInStockException;
use App\Packages\Exceptions\Checkout\UnsupportedDeliveryTypeException;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use Illuminate\Support\Facades\App;
use Money\Money;

class GetSummary
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
    ) {
    }

    /**
     * @throws EmptyShopCardException
     * @throws InsufficientQtyOfGoodsInStockException
     * @throws UnsupportedDeliveryTypeException
     */
    public function __invoke(GetSummaryData $data): SummaryData
    {
        $items = $this->checkoutService->getShopCartItems();

        if (!config('app.debug')) {
            /** @var ProductUseCase $productUseCase */
            $productUseCase = App::make(ProductUseCase::class);

            $productUseCase->checkStock($items);
        }

        $summary = $this->checkoutService->getFinalPrice($items);

        $delivery = $this->getDeliveryPrice($data->deliveryData);
        $summary = $summary->add($delivery);

        return new SummaryData($summary, $delivery);
    }

    /**
     * @throws UnsupportedDeliveryTypeException
     */
    private function getDeliveryPrice(GetSummaryDeliveryData $data): Money
    {
        if ($data->currierDeliveryId !== null) {
            return $this->getCurrierDeliveryPrice($data->currierDeliveryId);
        }

        if ($data->pvzId !== null) {
            return $this->getPvzDeliveryPrice($data->pvzId);
        }

        throw new UnsupportedDeliveryTypeException();
    }

    /**
     * Retrieves the delivery price for a given currier delivery ID.
     *
     * @param string $currierDeliveryId The ID of the currier delivery.
     * @return Money The delivery price.
     */
    private function getCurrierDeliveryPrice(string $currierDeliveryId): Money
    {
        // Retrieve the currier delivery information from the client
        $delivery = $this->deliveryModuleClient->getCurrierDelivery($currierDeliveryId);

        // Return the delivery price
        return $delivery->price;
    }

    /**
     * Retrieves the delivery price for a specific PVZ (Pickup Point).
     *
     * @param int $pvzId The ID of the PVZ.
     * @return Money The delivery price.
     */
    private function getPvzDeliveryPrice(int $pvzId): Money
    {
        // Retrieve the PVZ (Pickup Point) by its ID.
        $pvz = $this->deliveryModuleClient->getPvzById($pvzId);

        // Return the delivery price of the PVZ.
        return $pvz->price;
    }
}
