<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Services;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeRepository;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodeUsageRepository;
use App\Modules\Promotions\Modules\Promocodes\Support\Benefit\PromocodeBenefitActivatorInterface;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Promotions\Promocode\CreatePromocodeUsage;
use App\Packages\DataObjects\Promotions\Promocode\UpdatePromocodeUsage;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Exceptions\Promotions\ApplyPromocodeException;
use App\Packages\Exceptions\Promotions\CancelPromocodeException;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;
use Throwable;

class PromocodeBenefitService
{
    /**
     * @param iterable<PromocodeBenefitActivatorInterface> $benefitActivators
     */
    public function __construct(
        private readonly iterable $benefitActivators,
        private readonly PromocodeRepository $promocodeRepository,
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly PromocodeUsageRepository $promocodeUsageRepository
    ) {
    }

    public function getActive(string $shopCartToken): ?PromotionBenefit
    {
        $promocodeUsage = $this->promocodeUsageRepository->getActive($shopCartToken);
        return $promocodeUsage?->promotionBenefit;
    }

    public function getByPromotionExternalId(string $promotionExternalId): ?PromotionBenefit
    {
        return $this->promocodeRepository->getByPromotionExternalId($promotionExternalId);
    }

    /**
     * @throws Throwable
     */
    public function apply(PromotionBenefit $promotionBenefit): void
    {
        $this->preVerification();

        foreach ($this->benefitActivators as $activator) {
            if ($activator->canApply($promotionBenefit)) {
                $activator->apply($promotionBenefit);
            }
        }

        $shopCart = $this->shopCartModuleClient->getShopCart();
        $user = $this->usersModuleClient->getUser();

        $this->toggle($shopCart->token);

        $this->promocodeUsageRepository->create(
            $promotionBenefit,
            new CreatePromocodeUsage(
                $shopCart->token,
                $user->user_id
            )
        );
    }

    /**
     * @throws Throwable
     */
    public function cancel(): void
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();

        $promotionBenefit = $this->getActive($shopCart->token);

        if (!$promotionBenefit) {
            return;
        }

        foreach ($this->benefitActivators as $activator) {
            $activator->cancel($promotionBenefit);
        }

        $this->toggle(
            $shopCart->token
        );
    }

    private function toggle(string $shopCartToken): void
    {
        $promocodeUsage = $this->promocodeUsageRepository->getActive($shopCartToken);

        if ($promocodeUsage !== null) {
            $this->promocodeUsageRepository->update($promocodeUsage, new UpdatePromocodeUsage(
                $promocodeUsage->shop_cart_token,
                $promocodeUsage->user_id,
                false,
                $promocodeUsage->order_id
            ));
        }
    }

    /**
     * @throws ApplyPromocodeException
     */
    private function preVerification(): void
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();
        $items = collect($shopCart->items->all());

        if ($items->isEmpty()) {
            return;
        }

        $saleProducts = $this->getShopCartItemByPriceType($items, OfferPriceTypeEnum::SALE);

        $errors = [];
        /** @var ShopCartItemData $item */
        foreach ($saleProducts as $item) {
            $errors['products']['sale'][] = $item->sku;
        }

        if (!empty($errors)) {
            throw $this->createApplyPromocodeException($errors);
        }
    }

    private function getShopCartItemByPriceType(Collection $items, OfferPriceTypeEnum $type): Collection
    {
        return $items->where(function (ShopCartItemData $shopCartItemData) use ($type) {
            return $this->hasPriceType(
                collect($shopCartItemData->prices->all()),
                $type
            );
        });
    }

    /**
     * @param Collection<ProductOfferPriceData> $prices
     * @param OfferPriceTypeEnum $type
     * @return bool
     */
    private function hasPriceType(Collection $prices, OfferPriceTypeEnum $type): bool
    {
        foreach ($prices as $price) {
            if ($price->type === $type) {
                return true;
            }
        }

        return false;
    }

    private function createApplyPromocodeException(array $errorData = []): ApplyPromocodeException
    {
        $exception = new ApplyPromocodeException();
        $exception->setErrorData($errorData);

        return $exception;
    }
}
