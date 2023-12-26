<?php

namespace App\Modules\Catalog\Exceptions;

use App\Packages\Exceptions\DomainException;
use Throwable;

class IsActiveException extends DomainException
{
    public function __construct(string $message = "", int $code = 422, ?Throwable $previous = null)
    {
        $this->description = sprintf('is_active must be only true for %s', $message);
        parent::__construct($message, $code, $previous);
    }

    public function getDescription()
    {
        return $this->description;
    }
}
