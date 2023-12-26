<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Services;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Support\Validator\PromocodeValidatorInterface;

class PromocodeConditionService
{
    /**
     * @param iterable<PromocodeValidatorInterface> $promocodeValidators
     */
    public function __construct(
        private readonly iterable $promocodeValidators
    ) {
    }

    public function verify(PromotionBenefit $promocode): bool
    {
        foreach ($this->promocodeValidators as $validator) {
            if ($validator->validate($promocode) === false) {
                return false;
            }
        }

        return true;
    }
}
