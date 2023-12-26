<?php

declare(strict_types=1);

namespace App\Packages\DataTransformers;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if (! $value instanceof Money) {
            throw new \InvalidArgumentException('The value of type Money\Money expected');
        }

        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        $value = $formatter->format($value);

        return (int)$value;
    }
}
