<?php

declare(strict_types=1);

namespace App\Packages\DataCasts;

use Money\Money;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class MoneyCast implements Cast
{
    public function __construct(
        protected bool $isDecimal = false
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $context): ?Money
    {
        if (is_null($value)) {
            return null;
        }

        if ($this->isDecimal) {
            $value *= 100;
        }

        return Money::RUB((int)$value);
    }
}
