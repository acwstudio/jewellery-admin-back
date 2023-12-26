<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout;

use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'checkout_personal_data', type: 'object')]
class PersonalData extends Data
{
    public function __construct(
        #[MapName('phone_number')]
        #[WithTransformer(PhoneNumberTransformer::class)]
        #[Property(property: 'phone_number', type: 'string')]
        public readonly PhoneNumber $phoneNumber,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'surname', type: 'string', nullable: true)]
        public readonly ?string $surname,
        #[Property(property: 'email', type: 'string', nullable: true)]
        public readonly ?string $email = null,
        #[Property('patronymic', type: 'string', nullable: true)]
        public readonly ?string $patronymic = null
    ) {
    }
}
