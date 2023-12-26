<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;
use Carbon\Carbon;

class FinishAtPromocodeValidator implements PromocodeValidatorInterface
{
    public function validate(PromotionBenefit $promocode): bool
    {
        if ($promocode->promotion->condition->finish_at === null) {
            return true;
        }

        if ($promocode->promotion->condition->finish_at->greaterThanOrEqualTo(Carbon::now())) {
            return true;
        }

        return false;
    }
}
