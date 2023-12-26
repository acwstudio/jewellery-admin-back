<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\OTP;

use App\Packages\DataCasts\PhoneNumberCast;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'otp_check_otp_verification_data',
    description: 'Проверка OTP кода',
    type: 'object'
)]
class CheckOtpVerificationData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'string')]
        public readonly string $id,
        #[Property(property: 'phone', type: 'string')]
        #[WithCast(PhoneNumberCast::class)]
        public readonly PhoneNumber $phone,
        #[Property(property: 'code', type: 'string')]
        public readonly string $code,
    ) {
    }
}
