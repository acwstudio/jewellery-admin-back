<?php

declare(strict_types=1);

namespace App\Packages\Rules\Attributes;

use Spatie\LaravelData\Support\Validation\ValidationRule;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotEqual extends ValidationRule
{
    public function __construct(
        protected string $anotherField,
        protected bool $strict = false
    ) {
    }

    public function getRules(): array
    {
        return [new \App\Packages\Rules\NotEqual($this->anotherField, $this->strict)];
    }
}
