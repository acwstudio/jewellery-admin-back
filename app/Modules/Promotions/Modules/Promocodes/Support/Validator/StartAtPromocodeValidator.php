<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;
use Carbon\Carbon;

class StartAtPromocodeValidator implements PromocodeValidatorInterface
{
    public function validate(PromotionBenefit $promocode): bool
    {
        if ($promocode->promotion->condition->start_at === null) {
            return true;
        }

        if ($promocode->promotion->condition->start_at->lessThanOrEqualTo(Carbon::now())) {
            return true;
        }

        return false;
    }
}
