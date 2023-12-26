<?php
namespace App\Packages\Exceptions;

use App\Exceptions\DomainException;

class CarrierNotFoundException extends DomainException {
    protected $message = 'Could not get courier';
    protected $code = '1c-syncer_courier_not_found_exception';
    protected $description = 'Не удалось получить перевозчика';
}
