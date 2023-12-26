<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\CreateOrder;

use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(schema: 'create_order_personal_data', type: 'object')]
class CreateOrderPersonalData extends Data
{
    public function __construct(
        #[Property('phone', type: 'string')]
        #[WithCast(PhoneNumberCast::class)]
        public readonly PhoneNumber $phone,
        #[Property('email', type: 'string')]
        public readonly string $email,
        #[Property('name', type: 'string')]
        public readonly string $name,
        #[Property('surname', type: 'string')]
        public readonly string $surname,
        #[Property('patronymic', type: 'string', nullable: true)]
        public readonly ?string $patronymic = null
    ) {
    }
}
