<?php

declare(strict_types=1);

namespace App\Packages\Enums\Orders;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'order_status_enum', type: 'string')]
enum OrderStatusEnum: string
{
    case CREATED = 'Создан';
    case WAITING_FOR_PAYMENT = 'Ожидание оплаты';
    case PAID = 'Оплачен';
    case ASSEMBLY = 'Сборка';
    case ASSEMBLED = 'Собран';
    case ON_THE_PACKAGE = 'На упаковке';
    case FOR_DISPATCH = 'К отправке';
    case IN_DELIVERY = 'В доставке';
    case CANCELED = 'Отменен';
    case COMPLETED = 'Завершен';
}
