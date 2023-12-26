<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\DaData\Responses\DataObjects;

use Spatie\LaravelData\Data;

class MetroData extends Data
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $line,
        public readonly ?string $distance
    ) {
    }
}
