<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData\Responses\DataObjects;

use Spatie\LaravelData\Data;

class SuggestAddressData extends Data
{
    public function __construct(
        public readonly string $value,
        public readonly string $unrestricted_value,
        public AddressData $data
    ) {
    }
}
