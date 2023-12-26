<?php

declare(strict_types=1);

namespace App\Modules\Checkout\UseCase;

use App\Modules\Checkout\Services\CheckoutService;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Checkout\CheckoutData;
use App\Packages\DataObjects\Checkout\OrderData;
use App\Packages\DataObjects\Checkout\PersonalData;
use App\Packages\Exceptions\Checkout\EmptyShopCardException;
use App\Packages\Exceptions\Checkout\InsufficientQtyOfGoodsInStockException;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Money\Money;

class GetCheckout
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
        private readonly CheckoutService $checkoutService,
    ) {
    }

    /**
     * @throws EmptyShopCardException
     * @throws InsufficientQtyOfGoodsInStockException
     */
    public function __invoke(): CheckoutData
    {
        $user = $this->usersModuleClient->getUser();

        return new CheckoutData(
            $this->createPersonalData($user),
            $this->createOrderData(),
            $this->getSavedAddresses(),
            $this->getSavedPvz(),
        );
    }

    private function createPersonalData(User $user): PersonalData
    {
        return new PersonalData(
            $user->phone,
            $user->name,
            $user->surname,
            $user->email,
            $user->patronymic,
        );
    }

    /**
     * @throws EmptyShopCardException
     * @throws InsufficientQtyOfGoodsInStockException
     */
    private function createOrderData(): OrderData
    {
        $items = $this->checkoutService->getShopCartItems();

        if (!config('app.debug')) {
            /** @var ProductUseCase $productUseCase */
            $productUseCase = App::make(ProductUseCase::class);
            $productUseCase->checkStock($items);
        }

        $total = $this->checkoutService->calculateTotal($items);
        $final = $this->checkoutService->getFinalPrice($items);
        $discount = $total->subtract($final);

        return new OrderData(
            products: $this->checkoutService->getProducts($items),
            productsCount: $this->checkoutService->countProducts($items),
            productsTotal: $total,
            finalPrice: $final,
            discount: $discount,
            promocode: $this->checkoutService->getPromocode(),
        );
    }

    private function getSavedAddresses(): Collection
    {
        return $this->deliveryModuleClient->getSavedAddresses();
    }

    private function getSavedPvz(): Collection
    {
        return $this->deliveryModuleClient->getSavedPvz();
    }
}
