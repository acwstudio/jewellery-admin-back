<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Auth;

use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'auth_login_data',
    description: 'Авторизация',
    type: 'object'
)]
class AuthLoginData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'phone', type: 'string')]
        #[RequiredWith(['otp_id', 'otp_code']), WithCast(PhoneNumberCast::class)]
        public readonly ?PhoneNumber $phone = null,
        #[Property(property: 'otp_id', type: 'string')]
        #[RequiredWith(['phone', 'otp_code'])]
        public readonly ?string $otp_id = null,
        #[Property(property: 'otp_code', type: 'string')]
        #[RequiredWith(['phone', 'otp_id'])]
        public readonly ?string $otp_code = null,
        #[Property(property: 'email', type: 'string')]
        #[RequiredWith('password')]
        public readonly ?string $email = null,
        #[Property(property: 'password', type: 'string')]
        #[RequiredWith('email')]
        public readonly ?string $password = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
