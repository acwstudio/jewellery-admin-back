<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Payment\Enums;

enum OperationEnum: string
{
    /** Заказ создан */
    case CREATED = 'created';
    /** Операция удержания (холдирования) суммы */
    case APPROVED = 'approved';
    /** Операция завершения */
    case DEPOSITED = 'deposited';
    /** Операция отмены */
    case REVERSED = 'reversed';
    /** Операция возврата */
    case REFUNDED = 'refunded';
    /** Истекло время, отведенное на оплату заказа */
    case DECLINED_BY_TIMEOUT = 'declinedByTimeout';
    /** Подписка была создана Плательщиком */
    case SUBSCRIPTION_CREATED = 'subscriptionCreated';
}
