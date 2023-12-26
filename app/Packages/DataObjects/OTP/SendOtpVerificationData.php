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
    schema: 'otp_send_otp_verification_data',
    description: 'Отправка ОТП кода',
    type: 'object'
)]
class SendOtpVerificationData extends Data
{
    public function __construct(
        #[Property(property: 'phone', type: 'string')]
        #[WithCast(PhoneNumberCast::class)]
        public readonly PhoneNumber $phone,
        #[Property(property: 'recaptcha_token', type: 'string')]
        public readonly string $recaptcha_token,
    ) {
    }
}
