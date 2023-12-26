<?php

declare(strict_types=1);

namespace App\Modules\Payment\Enums;

enum PaymentStatusEnum: int
{
    /**
     * Новый
     */
    case NEW = 1;

    /**
     * Зарегистрирован
     */
    case REGISTERED = 2;

    /**
     * Захолдирован
     */
    case HELD = 3;

    /**
     * Подтвержден
     */
    case CONFIRMED = 4;

    /**
     * Отменен
     */
    case REVERSED = 5;

    /**
     * Оформлен возврат
     */
    case REFUNDED = 6;

    /**
     * ACS-авторизация
     */
    case ACS_AUTH = 7;

    /**
     * Ошибка
     */
    case DECLINED = 8;

    /**
     * Системная ошибка при обработке платежа
     */
    case ERROR = 9;
}
