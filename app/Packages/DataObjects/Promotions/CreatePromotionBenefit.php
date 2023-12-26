<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use App\Packages\DataCasts\MoneyCast;
use Money\Money;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CreatePromotionBenefit extends Data
{
    public function __construct(
        public readonly string $type,
        #[MapOutputName('type_form')]
        public readonly ?string $typeForm = null,
        #[MapInputName('promokode')]
        public readonly string $promocode = '',
        #[MapInputName('amount')]
        #[MapOutputName('nominal_amount')]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly ?Money $nominalAmount = null,
        #[MapInputName('percent')]
        #[MapOutputName('percent_amount')]
        public readonly ?int $percentAmount = null,
        #[MapInputName('maxAmount')]
        #[MapOutputName('max_nominal_amount')]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly ?Money $maxNominalAmount = null,
        #[MapInputName('circulation')]
        #[MapOutputName('use_count')]
        public readonly ?int $useCount = null,
        #[MapInputName('freeDelivery')]
        #[MapOutputName('is_free_delivery')]
        public readonly ?bool $isFreeDelivery = null,
        #[MapInputName('gift')]
        #[MapOutputName('is_gift')]
        public readonly ?bool $isGift = null,
        #[MapInputName('giftFromBasket')]
        #[MapOutputName('is_gift_from_shop_cart')]
        public readonly ?bool $isGiftFromShopCart = null,
        #[MapInputName('giftFromBasketCount')]
        #[MapOutputName('is_gift_from_shop_cart_count')]
        public readonly ?int $isGiftFromShopCartCount = null,
        #[DataCollectionOf(CreatePromotionBenefitGift::class)]
        public readonly ?DataCollection $gifts = null,
        #[DataCollectionOf(CreatePromotionBenefitProduct::class)]
        public readonly ?DataCollection $products = null
    ) {
    }
}
