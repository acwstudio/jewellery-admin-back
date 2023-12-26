<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Rapporto\Requests;

use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\PhoneNumber;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class RapportoSendData extends Data
{
    public function __construct(
        public readonly string $serviceId,
        public readonly string $pass,
        public readonly string $ptag,
        #[WithTransformer(PhoneNumberTransformer::class)]
        public readonly PhoneNumber $clientId,
        public readonly string $message,
        public readonly string $source
    ) {
    }
}
