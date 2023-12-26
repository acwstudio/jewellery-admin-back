<?php

declare(strict_types=1);

namespace App\Modules\Payment\Enums;

use Illuminate\Support\Arr;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'payment_type_enum', type: 'string')]
enum PaymentTypeEnum: int
{
    case CASH        = 1;
    case SBER_PAY = 2;
    case APPLE_PAY   = 3;
    case SAMSUNG_PAY = 4;
    case GOOGLE_PAY  = 5;

    public static function getCashlessPayment(): array
    {
        $paymentTypeEnums = Arr::keyBy(self::cases(), 'name');
        return Arr::except($paymentTypeEnums, 'CASH');
    }
}
