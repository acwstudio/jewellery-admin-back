<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\User;

use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\Support\PhoneNumber;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ImportUsersData extends Data
{
    public function __construct(
        public readonly string $first_name,
        public readonly ?string $last_name,
        public readonly ?string $second_name,
        #[WithCast(PhoneNumberCast::class)]
        public readonly PhoneNumber $phone,
        public readonly ?string $email,
    ) {
    }
}
