<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Promotions;

use App\Packages\Exceptions\DomainException;

class ApplyPromocodeException extends DomainException
{
    protected $code = 'promotions_module_apply_promocode_exception';
    protected $description = 'Невозможно применить промокод';

    public function setErrorData(array $data): void
    {
        $this->errorData = $data;
    }
}
