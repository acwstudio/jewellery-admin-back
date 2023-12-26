<?php

declare(strict_types=1);

namespace App\Packages\Exceptions;

use App\Exceptions\DomainException;

class ModelNotCreatedException extends DomainException
{
    protected $message = 'Model not created';
    protected $code = 'model_not_created_exception';
    protected $description = 'Не удалось создать модель';
}
