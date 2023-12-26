<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common;

use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\PhoneNumber;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class CustomerData extends Data
{
    public function __construct(
        public readonly IdData $ids = new IdData(),
        public readonly ?string $email = null,
        #[
            WithCast(PhoneNumberCast::class),
            WithTransformer(PhoneNumberTransformer::class)
        ]
        public readonly ?PhoneNumber $mobilePhone = null,
    ) {
    }
}
