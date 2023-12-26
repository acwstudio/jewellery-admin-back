<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;

class IsActivePromocodeValidator implements PromocodeValidatorInterface
{
    public function validate(PromotionBenefit $promocode): bool
    {
        return $promocode->promotion->is_active === true;
    }
}
