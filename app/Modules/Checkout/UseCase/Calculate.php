<?php

declare(strict_types=1);

namespace App\Modules\Checkout\UseCase;

use App\Modules\Checkout\Services\CheckoutService;
use App\Packages\DataObjects\Checkout\CalculateData;
use App\Packages\Exceptions\Checkout\EmptyShopCardException;
use Illuminate\Support\Collection;

class Calculate
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {
    }

    /**
     * @throws EmptyShopCardException
     */
    public function __invoke(?Collection $items = null): CalculateData
    {
        if (null === $items) {
            $items = $this->checkoutService->getShopCartItems();
        }

        return $this->createCalculateData($items);
    }

    /**
     * @throws EmptyShopCardException
     */
    private function createCalculateData(?Collection $items = null): CalculateData
    {
        $total = $this->checkoutService->calculateTotal($items);
        $final = $this->checkoutService->getFinalPrice($items);

        $discount = $total->subtract($final);

        return new CalculateData(
            products: $this->checkoutService->getProducts($items),
            productsCount: $this->checkoutService->countProducts($items),
            productsTotal: $total,
            finalPrice: $final,
            discount: $discount,
            promocode: $this->checkoutService->getPromocode()
        );
    }
}
