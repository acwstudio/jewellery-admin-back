<?php

declare(strict_types=1);

namespace App\Packages\DataTransformers;

use App\Packages\Support\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class PhoneNumberTransformer implements Transformer
{
    public function __construct(
        public readonly bool $withPlus = true
    ) {
    }

    public function transform(DataProperty $property, mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if (! $value instanceof PhoneNumber) {
            throw new \InvalidArgumentException('The value of type libphonenumber\PhoneNumber expected');
        }

        $formatted = PhoneNumberUtil::getInstance()->format($value, PhoneNumberFormat::E164);

        if ($this->withPlus === false) {
            $formatted = ltrim($formatted, '+');
        }

        return $formatted;
    }
}
