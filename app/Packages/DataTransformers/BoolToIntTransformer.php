<?php

declare(strict_types=1);

namespace App\Packages\DataTransformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class BoolToIntTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return intval($value);
    }
}
