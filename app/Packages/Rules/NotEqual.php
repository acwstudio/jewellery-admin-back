<?php

declare(strict_types=1);

namespace App\Packages\Rules;

use Exception;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class NotEqual implements Rule, DataAwareRule
{
    protected Collection $data;

    public function __construct(
        protected string $anotherField,
        protected bool $strict = false
    ) {
    }

    /**
     * @throws Exception
     */
    public function passes($attribute, $value): bool
    {
        if (!$this->data->has($this->anotherField)) {
            throw new Exception(
                sprintf('Field %s not found in data array', $this->anotherField)
            );
        }

        if ($this->strict) {
            return $this->data[$this->anotherField] !== $value;
        }

        return $this->data[$this->anotherField] != $value;
    }

    public function message()
    {
        return sprintf('The :attribute should not be equal to %s value', $this->anotherField);
    }

    public function setData($data): static
    {
        $this->data = new Collection($data);

        return $this;
    }
}
