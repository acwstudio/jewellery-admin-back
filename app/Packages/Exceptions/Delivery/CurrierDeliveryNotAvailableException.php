<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Delivery;

class CurrierDeliveryNotAvailableException extends DeliveryNotAvailableException
{
    protected $message = 'Currier delivery not available';
    protected $code = 'delivery-module_currier_delivery_not_available_exception';
    protected $description = 'Курьерская доставка невозможна';
}
