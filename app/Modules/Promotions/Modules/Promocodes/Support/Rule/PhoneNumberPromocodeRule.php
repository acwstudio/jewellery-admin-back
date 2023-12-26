<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Rule;

use App\Modules\Promotions\Enums\PromotionConditionTypeEnum;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Modules\Promotions\Models\PromotionConditionRulePhone;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\Exceptions\Promotions\ApplyPromocodeException;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;

class PhoneNumberPromocodeRule implements PromocodeRuleInterface
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
    ) {
    }

    /**
     * @throws ApplyPromocodeException
     */
    public function canApply(PromotionConditionRule $rule): bool
    {
        if (PromotionConditionTypeEnum::BY_RECIPIENT === $rule->type) {
            $this->validate($rule->phones);
            return true;
        }

        return false;
    }

    public function apply(ShopCartData $shopCart, PromotionConditionRule $rule): bool
    {
        return true;
    }

    /**
     * @param Collection<PromotionConditionRulePhone> $phones
     * @throws ApplyPromocodeException
     */
    private function validate(Collection $phones): void
    {
        $userPhone = $this->usersModuleClient->getUser()->phone;
        if (!$phones->contains('phone', $userPhone)) {
            throw new ApplyPromocodeException("This promo code does not belong to the current user.");
        }
    }
}
