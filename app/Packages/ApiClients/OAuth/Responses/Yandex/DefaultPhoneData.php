<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\OAuth\Responses\Yandex;

use Spatie\LaravelData\Data;

class DefaultPhoneData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $number,
    ) {
    }
}
