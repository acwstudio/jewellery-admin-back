<?php

declare(strict_types=1);

namespace App\Packages\DataCasts;

use App\Packages\Support\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class PhoneNumberCast implements Cast
{
    public function __construct(
        protected string $region = 'RU'
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return PhoneNumberUtil::getInstance()->parse(
            $value,
            $this->getRegion(),
            new PhoneNumber()
        );
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
