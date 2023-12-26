<?php

declare(strict_types=1);

namespace App\Exceptions;

class DomainException extends \Exception
{
    protected $description = 'Неизвестная ошибка';
    protected array $errorData = [];

    public function getDescription()
    {
        return $this->description;
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
