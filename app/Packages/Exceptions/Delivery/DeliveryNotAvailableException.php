<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Delivery;

use App\Packages\Exceptions\DomainException;

class DeliveryNotAvailableException extends DomainException
{
    protected $message = 'Delivery not available';
    protected $code = 'delivery-module_delivery_not_available_exception';
    protected $description = 'Доставка невозможна';
}
