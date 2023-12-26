<?php

declare(strict_types=1);

namespace App\Modules\Payment\Enums;

enum PaymentSystemTypeEnum: int
{
    /**
     * Сбербанк
     *
     * @var
     */
    case SBERBANK = 1;

    /**
     * Apple pay
     *
     * @var
     */
    case APPLE_PAY = 2;

    /**
     * Samsung pay
     *
     * @var
     */
    case SAMSUNG_PAY = 3;

    /**
     * Google pay
     *
     * @var
     */
    case GOOGLE_PAY = 4;
}
