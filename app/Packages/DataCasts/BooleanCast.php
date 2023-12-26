<?php

declare(strict_types=1);

namespace App\Packages\DataCasts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class BooleanCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
