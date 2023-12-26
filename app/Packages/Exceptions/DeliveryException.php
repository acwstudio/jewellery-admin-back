<?php

declare(strict_types=1);

namespace App\Packages\Exceptions;

class DeliveryException extends DomainException
{
    protected $code = 'delivery_module_delivery_exception';
}
