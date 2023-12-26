<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: self::SALE_PRICE)]
enum PromotionBenefitTypeFormEnum: string
{
    case PERCENT = 'Процент';
    case SALE_PRICE = 'Акционная цена';
    case SUM = 'Сумма';
    case GIFT = 'Подарок';
}
