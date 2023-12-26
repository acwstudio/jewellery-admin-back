<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Services;

use App\Packages\Support\PhoneNumber;
use Spatie\LaravelData\Data;

class CreatePromotionConditionRulePhone extends Data
{
    public function __construct(
        public readonly PhoneNumber $phone
    ) {
    }
}
