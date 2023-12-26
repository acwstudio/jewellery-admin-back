<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Checkout;

use App\Packages\Exceptions\DomainException;

class UnsupportedDeliveryTypeException extends DomainException
{
    protected $code = 'checkout_module_unsupported_delivery_type_exception';
    protected $message = 'Unsupported delivery type';
    protected $description = 'Данный вид доставки недоступен';
}
