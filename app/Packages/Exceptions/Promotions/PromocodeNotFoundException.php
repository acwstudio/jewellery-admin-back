<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Promotions;

use App\Packages\Exceptions\DomainException;

class PromocodeNotFoundException extends DomainException
{
    protected $code = 'promotions_module_promocode_not_found_exception';
    protected $description = 'Промокод не существует';
}
