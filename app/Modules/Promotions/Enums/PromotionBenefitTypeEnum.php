<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: self::PROMOCODE)]
enum PromotionBenefitTypeEnum: string
{
    case PROMOCODE = 'Промокод';
    case SALE = 'Акция';
}
