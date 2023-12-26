<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\OTP;

use App\Modules\OTP\Models\OtpVerification;
use App\Packages\DataTransformers\PhoneNumberTransformer;
use App\Packages\Support\PhoneNumber;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'otp_otp_verification_data',
    description: 'OTP данные',
    type: 'object'
)]
class OtpVerificationData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'string')]
        public readonly string $id,
        #[Property(property: 'phone', type: 'string')]
        #[WithTransformer(PhoneNumberTransformer::class)]
        public readonly PhoneNumber $phone,
    ) {
    }

    public static function fromModel(OtpVerification $model): self
    {
        return new self(
            $model->id,
            $model->phone
        );
    }
}
