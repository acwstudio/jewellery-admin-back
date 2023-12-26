<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Promotions;

use App\Packages\Exceptions\DomainException;

class CancelPromocodeException extends DomainException
{
    protected $code = 'promotions_module_cancel_promocode_exception';
    protected $description = 'Невозможно отменить промокод, промокод не установлен';
}
