<?php

declare(strict_types=1);

namespace App\Packages\DataCasts;

use BackedEnum;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\DataProperty;

class MappedEnumCast extends EnumCast
{
    public function __construct(
        protected array $map,
        protected ?string $type = null
    ) {
        parent::__construct();
    }

    public function cast(DataProperty $property, mixed $value, array $context): BackedEnum|Uncastable
    {
        if (!key_exists($value, $this->map)) {
            return Uncastable::create();
        }

        return parent::cast($property, $this->map[$value], $context);
    }
}
