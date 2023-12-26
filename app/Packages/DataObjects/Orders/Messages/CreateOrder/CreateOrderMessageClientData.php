<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Messages\CreateOrder;

use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\PhoneNumber;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class CreateOrderMessageClientData extends Data
{
    public function __construct(
        #[WithTransformer(PhoneNumberTransformer::class, withPlus: false)]
        public readonly PhoneNumber $phone,
        public readonly string $email,
        #[MapName('first_name')]
        public readonly string $name,
        #[MapName('last_name')]
        public readonly string $surname,
        #[MapName('second_name')]
        public readonly ?string $patronymic = null,
    ) {
    }
}
