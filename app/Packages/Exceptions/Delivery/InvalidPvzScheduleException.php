<?php

declare(strict_types=1);

namespace App\Packages\Exceptions\Delivery;

use App\Packages\Exceptions\DomainException;

class InvalidPvzScheduleException extends DomainException
{
    protected $message = 'Could not update pvz schedule';
    protected $code = 'core_delivery-module_invalid_pvz_schedule';
    protected $description = 'Не удалось обновить расписание ПВЗ';
}
