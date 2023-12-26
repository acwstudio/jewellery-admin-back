<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;

interface PromocodeValidatorInterface
{
    public function validate(PromotionBenefit $promocode): bool;
}
