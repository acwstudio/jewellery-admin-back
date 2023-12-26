<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Benefit;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodePriceRepository;
use App\Packages\DataObjects\Promotions\Promocode\CreatePromocodePrice;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Facades\ProductPrice;
use App\Packages\Facades\ShopCart;
use Illuminate\Support\Collection;
use Money\Money;

abstract class AbstractAmountPromocodeBenefitActivator implements PromocodeBenefitActivatorInterface
{
    public function __construct(
        private readonly PromocodePriceRepository $promocodePriceRepository
    ) {
    }

    abstract protected function getDiscount(PromotionBenefit $promotionBenefit, Money $total): Money;

    public function apply(PromotionBenefit $promotionBenefit): void
    {
        $shopCart = ShopCart::getShopCart();
        $total = ShopCart::getTotal();

        $discount = $this->getDiscount($promotionBenefit, $total);

        $ratios = $this->getAllocationRatios($shopCart, $total);
        $allocated = $discount->allocate($ratios->toArray());

        /** @var Collection $items */
        $items = $shopCart->items->toCollection();

        foreach ($items->zip($allocated) as $item) {
            /**
             * @var ShopCartItemData $shopCartItem
             * @var Money $discount
             */
            list($shopCartItem, $discount) = $item;
            $price = $this->getDiscountedPrice($shopCartItem, $discount);

            $this->promocodePriceRepository->create($promotionBenefit, new CreatePromocodePrice(
                $shopCartItem->product_offer_id,
                $shopCart->token,
                $price
            ));
        }
    }

    public function cancel(PromotionBenefit $promotionBenefit): void
    {
        $shopCart = ShopCart::getShopCart();
        $this->promocodePriceRepository->delete($promotionBenefit, $shopCart->token);
    }

    private function getAllocationRatios(ShopCartData $shopCart, Money $total): Collection
    {
        $ratios = collect();

        /** @var ShopCartItemData $item */
        foreach ($shopCart->items as $item) {
            $price = $this->getRegularMoney($item);
            $ratio = (float)$price->multiply($item->count)->getAmount() / (float)$total->getAmount();
            $ratios->push($ratio);
        }

        return $ratios;
    }

    private function getDiscountedPrice(ShopCartItemData $shopCartItem, Money $discount): Money
    {
        $regularMoney = $this->getRegularMoney($shopCartItem);
        return $regularMoney->subtract($discount->divide($shopCartItem->count));
    }

    private function getRegularMoney(ShopCartItemData $shopCartItem): Money
    {
        /** @var Collection $prices */
        $prices = $shopCartItem->prices->toCollection();
        return ProductPrice::getPrice(
            $prices,
            [
                OfferPriceTypeEnum::SALE,
                OfferPriceTypeEnum::PROMO,
                OfferPriceTypeEnum::PROMOCODE
            ]
        );
    }
}
