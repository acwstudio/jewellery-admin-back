<?php

declare(strict_types=1);

namespace App\Packages\Exceptions;

class GetPriceException extends DomainException
{
    protected $code = 'core_get_price_exception';
    protected $message = 'Get price failed';
    protected $description = 'Невозможно получить стоимость товара';
}
