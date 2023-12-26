<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Enums;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: self::ONE_TIME_SALES)]
enum PromotionConditionTypeEnum: string
{
    case ONE_TIME_SALES = 'За разовый объем продаж';
    case BY_RECIPIENT = 'По типу получателя';
    case BY_PRODUCT = 'По типу товара';
    case FOR_FROM_PAYMENT = 'За форму оплаты';
}
