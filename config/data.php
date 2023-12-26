<?php

declare(strict_types=1);

use App\Packages\Normalizers\RequestNormalizer;
use App\Packages\Support\PhoneNumber;
use Money\Money;

return [
    'normalizers' => [
        RequestNormalizer::class,
        Spatie\LaravelData\Normalizers\ModelNormalizer::class,
        Spatie\LaravelData\Normalizers\ArrayableNormalizer::class,
        Spatie\LaravelData\Normalizers\ObjectNormalizer::class,
        Spatie\LaravelData\Normalizers\ArrayNormalizer::class,
        Spatie\LaravelData\Normalizers\JsonNormalizer::class,
    ],
    'casts' => [
        DateTimeInterface::class => Spatie\LaravelData\Casts\DateTimeInterfaceCast::class,
        BackedEnum::class => Spatie\LaravelData\Casts\EnumCast::class,
        PhoneNumber::class => App\Packages\DataCasts\PhoneNumberCast::class,
        Money::class => App\Packages\DataCasts\MoneyCast::class,
    ],
];
