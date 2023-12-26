<?php

declare(strict_types=1);

namespace App\Packages\DataTransformers;

use Money\Money;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyDecimalTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value): ?Money
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof Money) {
            throw new \InvalidArgumentException('The value of type Money\Money expected');
        }

        $value = (float)$value->getAmount() / 100;

        return Money::RUB((int)($value));
    }
}
