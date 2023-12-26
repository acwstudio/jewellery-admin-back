<?php

declare(strict_types=1);

namespace App\Modules\Users\Exceptions;

use App\Packages\Exceptions\DomainException;
use Throwable;

class OldPasswordNotValidException extends DomainException
{
    protected $description = 'Old password not valid';
    protected $code = 422;
}
