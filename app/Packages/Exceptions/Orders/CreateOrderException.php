<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Orders;

use App\Packages\Exceptions\DomainException;

class CreateOrderException extends DomainException
{
    protected $message = 'Create order exception';
    protected $code = 'orders_module_create_order_exception';
    protected $description = 'Не удалось создать заказ';
}
