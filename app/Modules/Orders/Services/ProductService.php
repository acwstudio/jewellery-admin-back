<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Repositories\ProductRepository;
use App\Packages\DataObjects\Checkout\CalculateData;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderProductData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\ModuleClients\CheckoutModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;

class ProductService
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly CheckoutModuleClientInterface $checkoutModuleClient,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function create(Order $order): void
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();

        /** @var ShopCartItemData $item */
        foreach ($shopCart->items as $item) {
            $calculateData = $this->getCalculateData($item);
            $price = $calculateData->productsTotal;
            $amount = $calculateData->finalPrice;
            $discount = $calculateData->discount;

            $this->productRepository->create(
                $order,
                new CreateOrderProductData(
                    productOfferId: $item->product_offer_id,
                    guid: $item->external_id,
                    sku: $item->sku,
                    count: $item->count,
                    price: $price,
                    amount: $amount,
                    discount: $discount,
                    size: $item->size
                )
            );
        }
    }

    private function getCalculateData(ShopCartItemData $item): CalculateData
    {
        return $this->checkoutModuleClient->calculate(collect([$item]));
    }
}
