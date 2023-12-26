<?php

declare(strict_types=1);

namespace App\Packages\AttributeCasts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;
use Money\Money;

class MoneyCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        return new Money($value, new Currency('RUB'));
    }

    /**
     * @param Money $value
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        return intval($value->getAmount());
    }
}
